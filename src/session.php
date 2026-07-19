<?php

declare(strict_types=1);

/**
 * Starts a session.
 *
 * If the session is already active, this function does nothing.
 *
 * @return bool
 *     True on success, otherwise false.
 */
function b8_session_start(): bool
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }

    return session_start([
        'use_strict_mode' => true,
        'use_only_cookies' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure' => b8_https(),
    ]);
}

/**
 * Stores a value in the session.
 *
 * The session is started automatically if necessary.
 *
 * @param string $key
 *     Session key.
 *
 * @param mixed $value
 *     Session value.
 *
 * @return void
 */
function b8_session_set(
    string $key,
    mixed $value
): void
{
    b8_session_start();

    $_SESSION[$key] = $value;
}

/**
 * Returns a session value.
 *
 * The session is started automatically if necessary.
 *
 * @param string $key
 *     Session key.
 *
 * @param mixed $default
 *     Value returned if the key does not exist.
 *
 * @return mixed
 *     Session value or the default value.
 */
function b8_session_get(
    string $key,
    mixed $default = null
): mixed
{
    b8_session_start();

    return $_SESSION[$key] ?? $default;
}

/**
 * Deletes a session value.
 *
 * The session is started automatically if necessary.
 *
 * @param string $key
 *     Session key.
 *
 * @return void
 */
function b8_session_delete(string $key): void
{
    b8_session_start();

    unset($_SESSION[$key]);
}

/**
 * Destroys the current session.
 *
 * If no session exists, this function succeeds.
 *
 * @return bool
 *     True on success, otherwise false.
 */
function b8_session_destroy(): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return true;
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 3600,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]
        );
    }

    return session_destroy();
}