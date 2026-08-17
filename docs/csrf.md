# CSRF Protection

Base8 provides a small set of helper functions for cross-site request forgery protection.

The token is stored in the session and generated on first use. Sessions are started automatically when required.

---

## The Token

Use `b8_csrf_token()` to obtain the token for the current session.

```php
$token = b8_csrf_token();
```

The token is 64 hexadecimal characters. It is created once per session and stays the same for every request in that session, so multiple tabs and parallel AJAX requests all work with it.

---

## Forms

Use `b8_csrf_field()` to render a hidden input.

```php
<form method="post" action="/contact/send">

    <?= b8_csrf_field() ?>

    <input type="text" name="name">

    <button type="submit">Send</button>

</form>
```

This produces:

```html
<input type="hidden" name="csrf_token" value="...">
```

---

## Verifying

Use `b8_csrf_require()` to reject invalid requests.

```php
function send(): void
{
    b8_method_allow('POST');

    b8_csrf_require();

    // Only reached with a valid token.
}
```

When the token is valid the helper returns and the action continues. Otherwise Base8 renders `app/errors/403.php` if it exists and terminates the request.

Use `b8_csrf_verify()` when you want to handle the failure yourself.

```php
if (!b8_csrf_verify()) {
    b8_json(['error' => 'Invalid token.'], 403);
}
```

Both helpers read the token from the `csrf_token` POST field. If that field is absent they fall back to the `X-CSRF-Token` request header.

Comparison is timing-safe.

---

## AJAX

For requests without a form body, send the token as a header.

```php
<meta name="csrf-token" content="<?= b8_csrf_token() ?>">
```

```js
const token = document.querySelector('meta[name="csrf-token"]').content;

fetch('/contact/send', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': token
    },
    body: JSON.stringify({ name: 'Oliver' })
});
```

A JSON request body does not populate `$_POST`, so the header is the only way the token can be read in that case.

---

## Protecting a Whole Module

A module guard applies the check to every action at once.

```php
// app/modules/account.php

function _before(string $action, array $params): void
{
    if (b8_method('POST')) {
        b8_csrf_require();
    }
}
```

Verify only state-changing requests. `GET` requests must not require a token, otherwise ordinary navigation fails.

---

## Token Rotation

The token lives in the session, so `b8_session_destroy()` discards it and the next call to `b8_csrf_token()` issues a new one.

Destroy the session when a user logs in or out. This rotates the CSRF token together with the session identifier.

---

## Design Philosophy

Base8 never verifies tokens automatically.

There is no global middleware and no implicit protection. Each action, or each module guard, decides for itself. The framework supplies the token and a timing-safe comparison; where the check belongs is an application decision.
