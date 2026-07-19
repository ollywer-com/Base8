<?php

declare(strict_types=1);

/**
 * Encrypts a string using AES-256-CBC.
 *
 * The encrypted value is authenticated using HMAC-SHA256 and
 * returned as a Base64-encoded string suitable for storage in
 * cookies, sessions, JSON, databases, or HTML.
 *
 * The encryption key must be generated using b8_key().
 *
 * @param string $plaintext
 *     Plain text to encrypt.
 *
 * @param string $key
 *     Base64-encoded encryption key.
 *
 * @return string
 *     Base64-encoded encrypted string.
 *
 * @throws \InvalidArgumentException
 *     If the encryption key is invalid.
 *
 * @throws \RuntimeException
 *     If encryption fails.
 */
function b8_encrypt(string $plaintext, string $key): string
{
    $key = base64_decode($key, true);

    if ($key === false || strlen($key) !== 32) {
        throw new \InvalidArgumentException(
            'Invalid encryption key.'
        );
    }

    $cipher = 'AES-256-CBC';

    $ivLength = openssl_cipher_iv_length($cipher);

    $iv = random_bytes($ivLength);

    $ciphertext = openssl_encrypt(
        $plaintext,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($ciphertext === false) {
        throw new \RuntimeException(
            'Unable to encrypt data.'
        );
    }

    $hmac = hash_hmac(
        'sha256',
        $ciphertext,
        $key,
        true
    );

    return base64_encode(
        $iv . $hmac . $ciphertext
    );
}

/**
 * Decrypts a string previously encrypted by b8_encrypt().
 *
 * The HMAC is verified before the decrypted value is returned.
 *
 * The encryption key must be generated using b8_key().
 *
 * @param string $encrypted
 *     Base64-encoded encrypted string.
 *
 * @param string $key
 *     Base64-encoded encryption key.
 *
 * @return string|false
 *     Original plaintext or false if authentication or
 *     decryption fails.
 *
 * @throws \InvalidArgumentException
 *     If the encryption key is invalid.
 */
function b8_decrypt(string $encrypted, string $key): string|false
{
    $key = base64_decode($key, true);

    if ($key === false || strlen($key) !== 32) {
        throw new \InvalidArgumentException(
            'Invalid encryption key.'
        );
    }

    $cipher = 'AES-256-CBC';

    $ivLength = openssl_cipher_iv_length($cipher);

    $hmacLength = 32;

    $data = base64_decode($encrypted, true);

    if ($data === false) {
        return false;
    }

    if (strlen($data) < $ivLength + $hmacLength) {
        return false;
    }

    $iv = substr($data, 0, $ivLength);

    $hmac = substr(
        $data,
        $ivLength,
        $hmacLength
    );

    $ciphertext = substr(
        $data,
        $ivLength + $hmacLength
    );

    $calculated = hash_hmac(
        'sha256',
        $ciphertext,
        $key,
        true
    );

    if (!hash_equals($hmac, $calculated)) {
        return false;
    }

    $plaintext = openssl_decrypt(
        $ciphertext,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return $plaintext === false
        ? false
        : $plaintext;
}

/**
 * Generates a cryptographically secure encryption key.
 *
 * The returned key is Base64 encoded and intended for use with
 * b8_encrypt() and b8_decrypt().
 *
 * @return string
 *     Base64-encoded 256-bit encryption key.
 *
 * @throws \Exception
 *     If a secure random source is unavailable.
 */
function b8_key(): string
{
    return base64_encode(
        random_bytes(32)
    );
}

/**
 * Generates an RFC 4122 version 4 UUID.
 *
 * @param string|null $data
 *     Optional 16-byte binary seed.
 *
 * @return string
 *     UUID version 4.
 *
 * @throws \InvalidArgumentException
 *     If the supplied seed is not exactly 16 bytes.
 */
function b8_uuid(?string $data = null): string
{
    $data ??= random_bytes(16);

    if (strlen($data) !== 16) {
        throw new \InvalidArgumentException(
            'UUID seed must contain exactly 16 bytes.'
        );
    }

    $data[6] = chr(
        (ord($data[6]) & 0x0f) | 0x40
    );

    $data[8] = chr(
        (ord($data[8]) & 0x3f) | 0x80
    );

    return vsprintf(
        '%s%s-%s-%s-%s-%s%s%s',
        str_split(bin2hex($data), 4)
    );
}