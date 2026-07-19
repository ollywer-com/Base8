<?php

declare(strict_types=1);

/**
 * Sets the HTTP response status code.
 *
 * @param int $code
 *     HTTP response status code.
 *
 * @return void
 */
function b8_status(int $code): void
{
    http_response_code($code);
}

/**
 * Redirects the client to another URL.
 *
 * @param string $url
 *     Destination URL.
 *
 * @param int $code
 *     HTTP redirect status code.
 *
 * @return never
 */
function b8_redirect(string $url, int $code = 302): never
{
    http_response_code($code);

    header("Location: {$url}");

    exit();
}

/**
 * Sends a JSON response.
 *
 * @param mixed $data
 *     Data to encode as JSON.
 *
 * @param int $code
 *     HTTP response status code.
 *
 * @param int $flags
 *     Additional JSON encoding flags.
 *
 * @return never
 *
 * @throws JsonException
 */
function b8_json(
    mixed $data,
    int $code = 200,
    int $flags = 0
): never {
    http_response_code($code);

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(
        $data,
        JSON_THROW_ON_ERROR | $flags
    );

    exit();
}