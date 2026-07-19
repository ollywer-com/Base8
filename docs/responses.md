# Responses

Base8 provides a small set of helper functions for generating HTTP responses.

---

## HTTP Status

Use `b8_status()` to set the HTTP response status code.

```php
b8_status(404);
```

Example:

```php
b8_status(403);

echo 'Access denied.';
```

---

## Redirects

Use `b8_redirect()` to redirect the client to another URL.

```php
b8_redirect('/login');
```

By default, Base8 sends:

```text
302 Found
```

A custom status code may be supplied.

```php
b8_redirect('/login', 301);
```

Example:

```php
if (!b8_session_get('user_id')) {
    b8_redirect('/login');
}
```

`b8_redirect()` always terminates the current request.

---

## JSON Responses

Use `b8_json()` to return JSON.

```php
b8_json([
    'success' => true
]);
```

Output:

```json
{
    "success": true
}
```

The response automatically includes:

```text
Content-Type: application/json; charset=utf-8
```

and terminates the current request.

---

## HTTP Status with JSON

A custom HTTP status code may be supplied.

```php
b8_json(
    ['error' => 'Not found'],
    404
);
```

---

## JSON Encoding Flags

Additional JSON encoding flags may be supplied.

Example:

```php
b8_json(
    $data,
    200,
    JSON_PRETTY_PRINT
);
```

Multiple flags may be combined.

```php
b8_json(
    $data,
    200,
    JSON_PRETTY_PRINT
    | JSON_UNESCAPED_UNICODE
);
```

---

## JSON Errors

`b8_json()` uses:

```text
JSON_THROW_ON_ERROR
```

If JSON encoding fails, a `JsonException` is thrown.

Example:

```php
try {

    b8_json($data);

} catch (JsonException $e) {

    b8_status(500);

}
```

---

## Typical Usage

HTML response:

```php
echo 'Hello Base8!';
```

Redirect:

```php
b8_redirect('/dashboard');
```

JSON response:

```php
b8_json([
    'success' => true,
    'id' => 15
]);
```

HTTP status:

```php
b8_status(404);

echo 'Page not found.';
```

---

## Design Philosophy

Base8 intentionally provides only a few response helpers.

Applications continue to use native PHP for generating HTML while helper functions simplify common HTTP response tasks.