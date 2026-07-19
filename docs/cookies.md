# Cookies

Base8 provides a small set of helper functions for working with HTTP cookies.

The framework uses PHP's native cookie implementation while applying secure defaults.

---

## Setting a Cookie

Use `b8_cookie_set()` to create or update a cookie.

```php
b8_cookie_set(
    'theme',
    'dark'
);
```

By default, the cookie:

- is a session cookie
- is HTTP-only
- uses `SameSite=Lax`
- automatically enables the `Secure` attribute when the current request uses HTTPS

---

## Persistent Cookies

Specify the lifetime in seconds.

```php
b8_cookie_set(
    'theme',
    'dark',
    86400
);
```

This cookie expires after one day.

---

## Reading a Cookie

Retrieve a cookie value.

```php
$theme = b8_cookie_get('theme');
```

A default value may be supplied.

```php
$theme = b8_cookie_get(
    'theme',
    'light'
);
```

If the cookie does not exist, the default value is returned.

---

## Deleting a Cookie

Delete a cookie.

```php
b8_cookie_delete('theme');
```

The cookie is removed from both the browser and the current request.

---

## Custom Cookie Options

Additional options may be supplied.

```php
b8_cookie_set(
    'language',
    'en',
    86400,
    '/',
    '',
    true,
    true,
    'Strict'
);
```

Parameters:

| Parameter | Description |
| ---------- | ----------- |
| `$expires` | Cookie lifetime in seconds |
| `$path` | Cookie path |
| `$domain` | Cookie domain |
| `$secure` | Force or disable the Secure attribute (`null` = automatic) |
| `$httpOnly` | HTTP-only cookie |
| `$sameSite` | `Lax`, `Strict` or `None` |

---

## Secure Attribute

The `$secure` parameter defaults to `null`.

When `null` is used, Base8 automatically detects whether the current request uses HTTPS.

You may override this behavior.

Always secure:

```php
b8_cookie_set(
    'token',
    $token,
    3600,
    '/',
    '',
    true
);
```

Never secure:

```php
b8_cookie_set(
    'theme',
    'dark',
    86400,
    '/',
    '',
    false
);
```

---

## Login Example

```php
b8_cookie_set(
    'remember',
    $token,
    2592000
);
```

---

## Reading User Preferences

```php
$theme = b8_cookie_get(
    'theme',
    'light'
);

$language = b8_cookie_get(
    'language',
    'en'
);
```

---

## Removing User Preferences

```php
b8_cookie_delete('theme');

b8_cookie_delete('language');
```

---

## Design Philosophy

Base8 intentionally keeps cookie handling simple.

There are:

- no cookie objects
- no cookie collections
- no encryption layer
- no automatic serialization

The framework uses PHP's native cookie implementation with sensible default settings.