<?php

/*
|--------------------------------------------------------------------------
| Base8 Routing Tests
|--------------------------------------------------------------------------
|
| Internal development tool.
|
| Runs the built framework against a temporary application and checks
| which routes resolve to an action. Each request runs in its own PHP
| process, because the kernel terminates the request with exit().
|
|     php tests/routing.php
|
*/

declare(strict_types=1);

$framework = dirname(__DIR__) . '/framework/Base8.php';

if (!is_file($framework)) {
    exit("Missing framework/Base8.php. Run php build.php first.\n");
}

$fixture = sys_get_temp_dir() . '/base8-routing-' . bin2hex(random_bytes(4));

$files = [

    'public/runner.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        $_SERVER['REQUEST_URI'] = $argv[1];
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        register_shutdown_function(static function (): void {
            echo "\n#status:" . (http_response_code() ?: 200);
        });

        require getenv('BASE8_FRAMEWORK');

        Base8\Base8::run(__DIR__);
        PHP,

    'app/lib/helpers.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        function secret(): void
        {
            echo 'helper-executed';
        }
        PHP,

    'app/lib/guard.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        function _before(string $action, array $params): void
        {
            echo 'foreign-guard;';

            Base8\Base8::error(403);
        }
        PHP,

    'app/modules/open.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        require_once __DIR__ . '/../lib/guard.php';

        function index(): void
        {
            echo 'open-action';
        }
        PHP,

    'app/modules/index.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        require_once __DIR__ . '/../lib/helpers.php';

        function index(): void
        {
            echo 'index-action';
        }

        function hello(string $name = ''): void
        {
            echo "hello-action:$name";
        }
        PHP,

    'app/modules/pages.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        require_once __DIR__ . '/../lib/helpers.php';

        function changePassword(): void
        {
            echo 'change-password-action';
        }
        PHP,

    'app/modules/admin.php' => <<<'PHP'
        <?php

        declare(strict_types=1);

        function _before(string $action, array $params): void
        {
            echo "guard:$action;";

            if (getenv('BASE8_AUTH') !== 'yes') {
                Base8\Base8::error(401);
            }
        }

        function dashboard(): void
        {
            echo 'dashboard-action';
        }
        PHP,

];

foreach ($files as $path => $code) {

    $target = "$fixture/$path";

    if (!is_dir(dirname($target))) {
        mkdir(dirname($target), 0777, true);
    }

    file_put_contents($target, $code);
}

/**
 * Runs one request against the fixture application.
 *
 * @return array{int, string}
 *     Status code and response body.
 */
function request(string $fixture, string $framework, string $uri, bool $auth = false): array
{
    $env = getenv();
    $env['BASE8_FRAMEWORK'] = $framework;
    $env['BASE8_AUTH'] = $auth ? 'yes' : 'no';

    $process = proc_open(
        [PHP_BINARY, '-d', 'display_errors=stderr', "$fixture/public/runner.php", $uri],
        [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
        $pipes,
        null,
        $env
    );

    $output = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);

    if (!preg_match('/\n#status:(\d+)$/', $output, $match)) {
        return [0, $output . $errors];
    }

    return [(int) $match[1], substr($output, 0, -strlen($match[0]))];
}

$cases = [

    // [uri, auth, expected status, expected body]

    // Module actions still work.
    ['/', false, 200, 'index-action'],
    ['/index/hello/world', false, 200, 'hello-action:world'],
    ['/pages/change-password', false, 200, 'change-password-action'],

    // An unknown action in the index module is a 404, not a 500.
    ['/index/missing', false, 404, ''],

    // Built-in PHP functions are not routable.
    ['/pages/phpinfo', false, 404, ''],
    ['/pages/phpversion', false, 404, ''],
    ['/index/phpinfo', false, 404, ''],
    ['/index/phpversion', false, 404, ''],

    // Functions from other files are not routable.
    ['/pages/secret', false, 404, ''],
    ['/index/secret', false, 404, ''],

    // The guard still runs before the action, even for unknown actions.
    ['/admin/dashboard', false, 401, 'guard:dashboard;'],
    ['/admin/phpinfo', false, 401, 'guard:phpinfo;'],
    ['/admin/dashboard', true, 200, 'guard:dashboard;dashboard-action'],
    ['/admin/phpinfo', true, 404, 'guard:phpinfo;'],

    // A guard declared in another file is never called.
    ['/open', false, 200, 'open-action'],
    ['/open/missing', false, 404, ''],

];

$failed = 0;

foreach ($cases as [$uri, $auth, $status, $body]) {

    [$actualStatus, $actualBody] = request($fixture, $framework, $uri, $auth);

    $label = $uri . ($auth ? ' (auth)' : '');

    if ($actualStatus === $status && $actualBody === $body) {
        echo " PASS  $label → $status\n";
        continue;
    }

    $failed++;

    echo " FAIL  $label\n";
    echo "       expected $status " . var_export($body, true) . "\n";
    echo "       actual   $actualStatus " . var_export(substr($actualBody, 0, 200), true) . "\n";
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($fixture, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST
);

foreach ($iterator as $item) {
    $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
}

rmdir($fixture);

echo "\n" . (count($cases) - $failed) . '/' . count($cases) . " passed\n";

exit($failed === 0 ? 0 : 1);
