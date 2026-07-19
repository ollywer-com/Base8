<?php

declare(strict_types=1);

namespace Base8 {

    final class Base8
    {
        /**
         * Framework version.
         */
        public const VERSION = '1.1.1';

        /**
         * Minimum supported PHP version.
         */
        public const MIN_PHP_VERSION = 80000;

        /**
         * Application root directory.
         */
        private static string $root;

        /**
         * Framework entry point.
         *
         * @param string $root
         *     Absolute path to the application root directory.
         */
        public static function run(string $root): void
        {
            if (PHP_VERSION_ID < self::MIN_PHP_VERSION) {
                throw new \RuntimeException(
                    'Base8 requires PHP 8.0 or newer.'
                );
            }

            $root = realpath($root);

            if ($root === false || !is_dir($root)) {
                throw new \RuntimeException(
                    'Invalid public directory.'
                );
            }

            $root = dirname(rtrim($root, '/\\')) . DIRECTORY_SEPARATOR . 'app';

            if (!is_dir($root)) {
                throw new \RuntimeException(
                    'Application directory not found.'
                );
            }

            self::$root = $root;

            $module = 'index';
            $action = 'index';
            $params = [];

            $path = parse_url(
                $_SERVER['REQUEST_URI'] ?? '/',
                PHP_URL_PATH
            ) ?? '/';

            $base = rtrim(
                dirname($_SERVER['SCRIPT_NAME'] ?? ''),
                '/\\'
            );

            if (
                $base !== '' &&
                $base !== '/' &&
                str_starts_with($path, $base)
            ) {
                $path = substr($path, strlen($base));
            }

            $uri = trim($path, '/');

            if (strlen($uri) > 2048) {
                self::error(414);
            }

            if ($uri !== '') {

                $segments = explode('/', $uri);

                $module = strtolower($segments[0]);

                if (!self::isValidRouteSegment($module)) {
                    self::error(404);
                }

                if (isset($segments[1])) {

                    $action = strtolower($segments[1]);

                    if (!self::isValidRouteSegment($action)) {
                        self::error(404);
                    }

                    if (str_contains($action, '-')) {

                        $parts = explode('-', $action);

                        $action = array_shift($parts);

                        foreach ($parts as $part) {
                            $action .= ucfirst($part);
                        }
                    }

                    if (isset($segments[2])) {
                        $params = array_slice($segments, 2);
                    }
                }
            }

            $file = self::$root . "/modules/$module.php";

            if (!is_file($file)) {
                self::error($module === 'index' ? 500 : 404);
            }

            require $file;

            if (!function_exists($action)) {
                self::error($module === 'index' ? 500 : 404);
            }

            $action(...$params);
        }

        /**
         * Returns the application root directory.
         *
         * @return string
         *     Absolute path to the application root directory.
         */
        public static function root(): string
        {
            return self::$root;
        }

        /**
         * Validates a route segment.
         *
         * @param string $segment
         *     Route segment.
         *
         * @return bool
         *     True if the segment is valid, otherwise false.
         */
        private static function isValidRouteSegment(string $segment): bool
        {
            if ($segment === '' || $segment[0] === '_') {
                return false;
            }

            $length = strlen($segment);

            for ($i = 0; $i < $length; $i++) {

                $char = ord($segment[$i]);

                if (
                    ($char >= 48 && $char <= 57) ||
                    ($char >= 65 && $char <= 90) ||
                    ($char >= 97 && $char <= 122) ||
                    $char === 45
                ) {
                    continue;
                }

                return false;
            }

            return true;
        }

        /**
         * Terminates the current request with the specified HTTP status code.
         *
         * @param int $code
         *     HTTP status code.
         *
         * @return never
         */
        private static function error(int $code): never
        {
            http_response_code($code);

            $file = self::$root . "/errors/$code.php";

            if (is_file($file)) {
                require $file;
            }

            exit();
        }
    }

}