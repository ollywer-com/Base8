<?php

declare(strict_types=1);

/**
 * Returns the framework version.
 *
 * @return string
 *     Current Base8 version.
 */
function b8_version(): string
{
    return \Base8\Base8::VERSION;
}

/**
 * Returns the application root directory.
 *
 * @return string
 *     Absolute path to the application directory.
 */
function b8_root(): string
{
    return \Base8\Base8::root();
}

/**
 * Determines whether the current request uses HTTPS.
 *
 * @return bool
 *     True if the request uses HTTPS, otherwise false.
 */
function b8_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    return (
        ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
    );
}