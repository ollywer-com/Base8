<?php

/*
|--------------------------------------------------------------------------
| Base8 Release Builder
|--------------------------------------------------------------------------
|
| Internal development tool.
|
| Creates the Base8 Starter release package.
|
| This script is intended only for project maintainers.
|
*/

declare(strict_types=1);

$start = microtime(true);
$buildTime = date('Y-m-d H:i:s');

if (!extension_loaded('zip')) {
    exit("The ZIP extension is not enabled.\n");
}

require __DIR__ . '/framework/Base8.php';

$version = b8_version();

if ($version === '') {
    exit("VERSION file is empty.\n");
}

$releaseDir = __DIR__ . '/release';

$zipName = "Base8-Starter-v{$version}.zip";

$zipFile = $releaseDir . DIRECTORY_SEPARATOR . $zipName;

$items = [

    'app',
    'docs',
    'framework',
    'public',

    'README.md',
    'LICENSE',
    'CHANGELOG.md',

];

if (!is_dir($releaseDir)) {

    mkdir($releaseDir, 0777, true);

}

if (is_file($zipFile)) {

    unlink($zipFile);

}

$zip = new ZipArchive();

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {

    exit("Cannot create release archive.\n");

}

foreach ($items as $item) {

    $path = __DIR__ . DIRECTORY_SEPARATOR . $item;

    if (!file_exists($path)) {

        continue;

    }

    if (is_dir($path)) {

        addDirectory(
            $zip,
            $path,
            $item
        );

        continue;

    }

    $zip->addFile(
        $path,
        $item
    );

}

$zip->close();

$duration = number_format(
    (microtime(true) - $start) * 1000,
    2
);

$size = number_format(
    filesize($zipFile) / 1024,
    2
);

$count = count($items);

if (PHP_SAPI === 'cli') {
    exit();
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Base8 Release Builder</title><style>body{margin:40px;background:#f5f5f5;color:#222;font:15px Consolas,Monaco,monospace}.card{max-width:720px;background:#fff;border:1px solid #ddd;padding:24px}h1{margin:0 0 24px;font-size:24px}table{width:100%;border-collapse:collapse}td{padding:8px 0}td:first-child{width:180px;font-weight:bold}.success{margin-top:24px;color:#198754;font-weight:bold}</style></head><body><div class="card"><h1>Base8 Release Builder</h1><table><tr><td>Version</td><td><?= $version ?></td></tr><tr><td>Package</td><td>Base8 Starter</td></tr><tr><td>Items</td><td><?= $count ?></td></tr><tr><td>Archive</td><td><?= $zipName ?></td></tr><tr><td>Output</td><td>release/</td></tr><tr><td>Archive size</td><td><?= $size ?> KB</td></tr><tr><td>Build time</td><td><?= $buildTime ?></td></tr><tr><td>Duration</td><td><?= $duration ?> ms</td></tr></table><p class="success">✔ Release completed successfully.</p></div></body></html><?php

function addDirectory(
    ZipArchive $zip,
    string $directory,
    string $zipRoot
): void {

    $zip->addEmptyDir($zipRoot);

    $items = scandir($directory);

    if ($items === false) {
        return;
    }

    foreach ($items as $item) {

        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . DIRECTORY_SEPARATOR . $item;
        $local = $zipRoot . '/' . $item;

        if (is_dir($path)) {

            addDirectory($zip, $path, $local);
            continue;
        }

        $zip->addFile($path, $local);
    }
}
