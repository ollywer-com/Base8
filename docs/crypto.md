# Cryptography

Base8 provides helper functions for common cryptographic operations.

The framework supports:

- authenticated encryption
- secure key generation
- UUID version 4 generation

---

## Generating an Encryption Key

Generate a new encryption key.

```php
$key = b8_key();
```

The returned key is Base64 encoded and contains 256 bits of cryptographically secure random data.

Store the key in a secure location.

Never commit encryption keys to version control.

---

## Encrypting Data

Encrypt a string.

```php
$encrypted = b8_encrypt(
    'Hello Base8!',
    $key
);
```

The returned value is Base64 encoded and may be safely stored in:

- cookies
- sessions
- databases
- JSON
- HTML forms

Base8 uses:

- AES-256-CBC
- HMAC-SHA256

to provide both confidentiality and integrity.

---

## Decrypting Data

Decrypt a previously encrypted value.

```php
$plaintext = b8_decrypt(
    $encrypted,
    $key
);
```

If decryption succeeds:

```text
Hello Base8!
```

If authentication fails or the encrypted data is invalid:

```php
false
```

is returned.

Always check the return value.

```php
$data = b8_decrypt(
    $encrypted,
    $key
);

if ($data === false) {

    b8_status(400);

    exit();
}
```

---

## Invalid Keys

Encryption keys must be generated using:

```php
b8_key();
```

Supplying an invalid key causes an exception.

```php
try {

    $encrypted = b8_encrypt(
        'Hello',
        $key
    );

} catch (\InvalidArgumentException $e) {

    //
}
```

---

## UUID Generation

Generate a Version 4 UUID.

```php
$id = b8_uuid();
```

Example:

```text
6d8d76a3-8fef-486d-92cf-f21cb90e4f87
```

You may also supply a 16-byte binary seed.

```php
$id = b8_uuid(
    random_bytes(16)
);
```

---

## Typical Usage

Generate a key once.

```php
$key = b8_key();
```

Encrypt data.

```php
$token = b8_encrypt(
    'user:15',
    $key
);
```

Decrypt data.

```php
$user = b8_decrypt(
    $token,
    $key
);

if ($user === false) {

    b8_status(401);

    exit();
}
```

Generate a UUID.

```php
$id = b8_uuid();
```

---

## Security Notes

- Keep encryption keys secret.
- Use a different key for each application.
- Never modify encrypted values manually.
- Always verify the return value of `b8_decrypt()`.

---

## Design Philosophy

Base8 intentionally provides only a small cryptography API.

There are:

- no key stores
- no certificate management
- no password hashing helpers
- no JWT implementation
- no custom cryptographic algorithms

The framework builds on PHP's native cryptographic extensions using secure default settings.