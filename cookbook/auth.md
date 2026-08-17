# Protecting Endpoints

This guide demonstrates how to protect an API module using a bearer token.

Protection happens in one place: the module guard. Individual actions stay free of authorization code.

---

# Project Structure

Create the following module.

```text
app/

└── modules/

    reports.php
```

---

# The Guard

If a module declares `_before()`, Base8 calls it before the action.

```php
function _before(string $action, array $params): void
{
    // Runs before every action in this module.
}
```

To reject a request, terminate it from inside the guard. To allow it, return normally.

The guard runs even when the requested action does not exist, so an unauthorized client cannot discover which actions the module defines.

---

# Reading the Token

A bearer token arrives in the `Authorization` request header.

```http
Authorization: Bearer 9f2c...
```

```php
function _token(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? '';

    if (
        preg_match(
            '/^Bearer\s+(\S+)$/i',
            $header,
            $matches
        ) !== 1
    ) {
        return null;
    }

    return $matches[1];
}
```

Helper functions inside a module should begin with an underscore. Base8 rejects route segments starting with `_`, so `_token` can never be reached as a URL.

---

# Verifying the Token

```php
function _valid(string $token): bool
{
    $expected = getenv('API_TOKEN');

    if (!is_string($expected) || $expected === '') {
        return false;
    }

    return hash_equals($expected, $token);
}
```

Two rules matter here.

Compare with `hash_equals()`, never with `===`. A plain comparison returns as soon as two bytes differ, which leaks how much of the token was correct.

Fail closed. When no token is configured, the function returns `false` rather than allowing the request.

Never commit a token to the repository. Store it in the environment, exactly as you would with a key from `b8_key()`.

---

# The Complete Module

Create:

```text
app/modules/reports.php
```

```php
<?php

declare(strict_types=1);

function _before(string $action, array $params): void
{
    if ($action === 'status') {
        return;
    }

    $token = _token();

    if ($token === null || !_valid($token)) {

        header('WWW-Authenticate: Bearer');

        b8_json(
            [
                'success' => false,
                'message' => 'Unauthorized.'
            ],
            401
        );

    }
}

function _token(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? '';

    if (
        preg_match(
            '/^Bearer\s+(\S+)$/i',
            $header,
            $matches
        ) !== 1
    ) {
        return null;
    }

    return $matches[1];
}

function _valid(string $token): bool
{
    $expected = getenv('API_TOKEN');

    if (!is_string($expected) || $expected === '') {
        return false;
    }

    return hash_equals($expected, $token);
}

function status(): void
{
    b8_json([
        'status' => 'ok'
    ]);
}

function summary(): void
{
    b8_json([
        'orders' => 128,
        'revenue' => 9420
    ]);
}

function rebuild(): void
{
    b8_method_allow('POST');

    b8_json([
        'success' => true
    ]);
}
```

`status()` is listed first in the guard and returns early, so it stays public. Every other action requires a token.

---

# Public Endpoint

```text
GET /reports/status
```

```json
{
    "status": "ok"
}
```

---

# Protected Endpoint

Without a token:

```text
GET /reports/summary
```

```http
HTTP/1.1 401 Unauthorized
WWW-Authenticate: Bearer
```

```json
{
    "success": false,
    "message": "Unauthorized."
}
```

With a token:

```text
GET /reports/summary
Authorization: Bearer 9f2c...
```

```json
{
    "orders": 128,
    "revenue": 9420
}
```

An unknown action returns the same 401, not a 404:

```text
GET /reports/does-not-exist
```

```http
HTTP/1.1 401 Unauthorized
```

---

# Passing the Authorization Header

Apache does not expose the `Authorization` header to PHP by default.

This is deliberate on Apache's part, and it is not limited to one SAPI — the header is withheld under `mod_php` just as it is under PHP-FPM, FastCGI, and CGI. Without the rule below, `$_SERVER['HTTP_AUTHORIZATION']` is never set and every request is rejected as unauthorized.

The `.htaccess` shipped with Base8 already contains this rule. If you wrote your own, add it:

```apache
<IfModule mod_rewrite.c>

    RewriteCond %{HTTP:Authorization} .

    RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

</IfModule>
```

On Apache 2.4.13 and newer you may instead set:

```apache
CGIPassAuth On
```

Place the rule before the front controller rule, and note the `-` substitution: it sets the variable without rewriting the URL, so processing continues to the front controller.

Because the front controller performs an internal redirect, the value then arrives under both `HTTP_AUTHORIZATION` and `REDIRECT_HTTP_AUTHORIZATION`. This is why `_token()` above reads both.

If your tokens appear to be ignored, check this first.

Nginx passes the header to PHP-FPM by default.

---

# Use HTTPS

A bearer token is a plaintext credential. Over HTTP it is visible to anyone on the network.

```php
if (!b8_https()) {

    b8_json(
        [
            'success' => false,
            'message' => 'HTTPS required.'
        ],
        403
    );

}
```

---

# CSRF Does Not Apply Here

CSRF exists because browsers attach cookies to cross-site requests automatically. The victim's credential travels with a request they never intended to send.

An `Authorization` header is never attached automatically. A cross-site page cannot add it. There is nothing to forge, so a token-authenticated API does not need CSRF protection.

This changes the moment the same endpoints also accept a session cookie. If a logged-in browser session can call the endpoint, the endpoint needs CSRF protection again:

```php
function _before(string $action, array $params): void
{
    if (b8_method('POST')) {
        b8_csrf_require();
    }
}
```

See **csrf.md** for the session-based approach.

Choose one authentication model per module. Accepting both in the same place is how the CSRF hole gets reintroduced.

---

# Summary

In this guide you learned how to:

- protect a whole module with `_before()`
- keep selected actions public
- read a bearer token from the `Authorization` header
- compare secrets in constant time
- return a correct 401 response
- forward the `Authorization` header on Apache
- decide when CSRF protection is required
