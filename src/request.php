<?php

declare(strict_types=1);

/**
 * Returns a GET parameter or the entire GET array.
 *
 * @param string|null $key
 *     GET parameter name.
 *     If null, the entire GET array is returned.
 *
 * @param mixed $default
 *     Value returned when the parameter does not exist.
 *
 * @return mixed
 *     GET parameter value, the default value, or the entire GET array.
 */
function b8_get(?string $key = null, mixed $default = null): mixed
{
    if ($key === null) {
        return $_GET;
    }

    return $_GET[$key] ?? $default;
}

/**
 * Returns a POST parameter or the entire POST array.
 *
 * @param string|null $key
 *     POST parameter name.
 *     If null, the entire POST array is returned.
 *
 * @param mixed $default
 *     Value returned when the parameter does not exist.
 *
 * @return mixed
 *     POST parameter value, the default value, or the entire POST array.
 */
function b8_post(?string $key = null, mixed $default = null): mixed
{
    if ($key === null) {
        return $_POST;
    }

    return $_POST[$key] ?? $default;
}

/**
 * Returns request data based on the current HTTP method.
 *
 * GET requests use the GET array.
 * POST requests use the POST array.
 *
 * @param string|null $key
 *     Request parameter name.
 *     If null, the entire request array is returned.
 *
 * @param mixed $default
 *     Value returned when the parameter does not exist.
 *
 * @return mixed
 *     Request parameter value, the default value, or the entire request array.
 */
function b8_request(?string $key = null, mixed $default = null): mixed
{
    if (b8_method('POST')) {
        return b8_post($key, $default);
    }

    return b8_get($key, $default);
}

/**
 * Returns the current HTTP request method or compares it.
 *
 * @param string|null $method
 *     HTTP method to compare (GET, POST, PUT, PATCH, DELETE, etc.).
 *     If null, the current request method is returned.
 *
 * @return string|bool
 *     Returns the current HTTP request method when no argument is supplied.
 *     Otherwise returns true if the supplied method matches the current
 *     request method, or false if it does not.
 */
function b8_method(?string $method = null): string|bool
{
    $current = $_SERVER['REQUEST_METHOD'] ?? 'CLI';

    if ($method === null) {
        return $current;
    }

    return strtoupper($method) === strtoupper($current);
}