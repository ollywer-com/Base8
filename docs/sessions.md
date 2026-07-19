# Sessions

Base8 provides a small set of helper functions for working with PHP sessions.

Sessions are started automatically when required. In most applications there is no need to call `b8_session_start()` manually.

---

## Starting a Session

Start a session explicitly.

```php
b8_session_start();
```

If a session is already active, the function does nothing and returns `true`.

Most applications do not need to call this function directly.

---

## Storing Data

Store a value in the current session.

```php
b8_session_set('user_id', 15);
```

Any PHP value may be stored.

```php
b8_session_set('user', $user);

b8_session_set('cart', $cart);

b8_session_set('authenticated', true);
```

The session is started automatically if necessary.

---

## Retrieving Data

Retrieve a session value.

```php
$userId = b8_session_get('user_id');
```

A default value may be supplied.

```php
$userId = b8_session_get('user_id', 0);
```

If the key does not exist, the default value is returned.

---

## Deleting Data

Remove a single session value.

```php
b8_session_delete('user_id');
```

Example:

```php
b8_session_delete('flash_message');
```

The session itself remains active.

---

## Destroying the Session

Destroy the current session.

```php
b8_session_destroy();
```

This function:

- removes all session data
- deletes the session cookie
- destroys the PHP session

If no session exists, the function succeeds without error.

---

## Login Example

```php
function login(): void
{
    b8_session_set('user_id', 15);

    b8_redirect('/dashboard');
}
```

---

## Authentication Check

```php
function dashboard(): void
{
    if (!b8_session_get('user_id')) {
        b8_redirect('/login');
    }

    b8_view('dashboard');
}
```

---

## Logout Example

```php
function logout(): void
{
    b8_session_destroy();

    b8_redirect('/');
}
```

---

## Session Security

Base8 configures PHP sessions using secure defaults.

- Strict session mode is enabled.
- Sessions use cookies only.
- Session cookies are HTTP-only.
- SameSite is set to `Lax`.
- Secure cookies are enabled automatically when the current request uses HTTPS.

---

## Design Philosophy

Base8 intentionally keeps session handling simple.

There are:

- no session objects
- no flash session API
- no session drivers
- no custom storage layer

The framework uses PHP's native session implementation with secure default settings.