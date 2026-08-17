<?php

declare(strict_types=1);

/**
 * Returns the CSRF token for the current session.
 *
 * The token is generated once per session and reused for every request.
 * The session is started automatically if necessary.
 *
 * @return string
 *     CSRF token, 64 hexadecimal characters.
 */
function b8_csrf_token(): string
{
    $token = b8_session_get('_b8_csrf');

    if (is_string($token) && $token !== '') {
        return $token;
    }

    $token = bin2hex(
        random_bytes(32)
    );

    b8_session_set('_b8_csrf', $token);

    return $token;
}

/**
 * Returns a hidden form input containing the CSRF token.
 *
 * @return string
 *     HTML input element.
 */
function b8_csrf_field(): string
{
    $token = htmlspecialchars(
        b8_csrf_token(),
        ENT_QUOTES,
        'UTF-8'
    );

    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verifies a CSRF token against the current session.
 *
 * When no token is supplied, it is read from the csrf_token POST field and,
 * if that is absent, from the X-CSRF-Token request header.
 *
 * Comparison is timing-safe.
 *
 * @param string|null $token
 *     CSRF token to verify.
 *     If null, the token is read from the current request.
 *
 * @return bool
 *     True if the token is valid, otherwise false.
 */
function b8_csrf_verify(?string $token = null): bool
{
    $expected = b8_session_get('_b8_csrf');

    if (!is_string($expected) || $expected === '') {
        return false;
    }

    if ($token === null) {

        $token = b8_post('csrf_token');

        if (!is_string($token) || $token === '') {
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }
    }

    if (!is_string($token) || $token === '') {
        return false;
    }

    return hash_equals($expected, $token);
}

/**
 * Requires a valid CSRF token.
 *
 * Returns silently when the token is valid. Otherwise terminates the request
 * with 403.
 *
 * @param string|null $token
 *     CSRF token to verify.
 *     If null, the token is read from the current request.
 *
 * @return void
 */
function b8_csrf_require(?string $token = null): void
{
    if (b8_csrf_verify($token)) {
        return;
    }

    b8_error(403);
}