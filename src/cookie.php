<?php

declare(strict_types=1);

/**
 * Sets a cookie.
 *
 * @param string $name
 *     Cookie name.
 *
 * @param string $value
 *     Cookie value.
 *
 * @param int $expires
 *     Cookie lifetime in seconds.
 *     Set to 0 for a session cookie.
 *
 * @param string $path
 *     Cookie path.
 *
 * @param string $domain
 *     Cookie domain.
 *
 * @param bool|null $secure
 *     Whether the cookie should be sent only over HTTPS.
 *     If null, the current request protocol is used.
 *
 * @param bool $httpOnly
 *     Whether the cookie is accessible only through HTTP.
 *
 * @param string $sameSite
 *     SameSite policy.
 *     Allowed values: Lax, Strict, None.
 *
 * @return bool
 *     True on success, otherwise false.
 */
function b8_cookie_set(
    string $name,
    string $value,
    int $expires = 0,
    string $path = '/',
    string $domain = '',
    ?bool $secure = null,
    bool $httpOnly = true,
    string $sameSite = 'Lax'
): bool {

    $secure ??= b8_https();

    return setcookie(
        $name,
        $value,
        [
            'expires' => $expires > 0
                ? time() + $expires
                : 0,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]
    );
}

/**
 * Returns a cookie value.
 *
 * @param string $name
 *     Cookie name.
 *
 * @param mixed $default
 *     Value returned if the cookie does not exist.
 *
 * @return mixed
 *     Cookie value or the default value.
 */
function b8_cookie_get(
    string $name,
    mixed $default = null
): mixed
{
    return $_COOKIE[$name] ?? $default;
}

/**
 * Deletes a cookie.
 *
 * @param string $name
 *     Cookie name.
 *
 * @param string $path
 *     Cookie path.
 *
 * @param string $domain
 *     Cookie domain.
 *
 * @param bool|null $secure
 *     Whether the cookie should be sent only over HTTPS.
 *     If null, the current request protocol is used.
 *
 * @return bool
 *     True on success, otherwise false.
 */
function b8_cookie_delete(
    string $name,
    string $path = '/',
    string $domain = '',
    ?bool $secure = null
): bool {

    $secure ??= b8_https();

    unset($_COOKIE[$name]);

    return setcookie(
        $name,
        '',
        [
            'expires' => time() - 3600,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );
}