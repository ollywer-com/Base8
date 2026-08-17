# Requests

Base8 provides a small set of helper functions for accessing HTTP request data.

All request helpers return raw request values. Input validation and sanitization are the responsibility of the application.

---

## GET Parameters

Use `b8_get()` to access query string parameters.

```text
/users?id=15
```

```php
$id = b8_get('id');
```

Returns:

```text
15
```

A default value may be supplied.

```php
$page = b8_get('page', 1);
```

---

## Entire GET Array

Calling `b8_get()` without arguments returns the complete `$_GET` array.

```php
$get = b8_get();
```

Equivalent to:

```php
$get = $_GET;
```

---

## POST Parameters

Use `b8_post()` to access POST data.

```php
$username = b8_post('username');
$password = b8_post('password');
```

A default value may be supplied.

```php
$page = b8_post('page', 1);
```

---

## Entire POST Array

Calling `b8_post()` without arguments returns the complete `$_POST` array.

```php
$post = b8_post();
```

Equivalent to:

```php
$post = $_POST;
```

---

## Request Data

`b8_request()` automatically selects the appropriate request source.

- GET requests use `$_GET`
- POST requests use `$_POST`

Example:

```php
$name = b8_request('name');
```

If the request method is GET, this is equivalent to:

```php
$name = b8_get('name');
```

If the request method is POST, this is equivalent to:

```php
$name = b8_post('name');
```

A default value may also be supplied.

```php
$page = b8_request('page', 1);
```

Calling `b8_request()` without arguments returns the active request array.

---

## Request Method

Use `b8_method()` to determine the current HTTP request method.

Return the current method:

```php
$method = b8_method();
```

Example result:

```text
POST
```

Compare the current method:

```php
if (b8_method('POST')) {

}
```

Another example:

```php
if (b8_method('GET')) {

}
```

Method comparison is case-insensitive.

```php
b8_method('post');
b8_method('Post');
b8_method('POST');
```

All produce the same result.

---

## Restricting Methods

`b8_method_allow()` restricts an action to one or more HTTP methods.

```php
function save(): void
{
    b8_method_allow('POST');

    // Only reached on POST.
}
```

Several methods may be allowed:

```php
b8_method_allow('POST', 'PUT', 'PATCH');
```

When the request method matches, the helper returns and the action continues.

When it does not match, Base8 sends an `Allow` header, renders `app/errors/405.php` if it exists, and terminates the request:

```text
HTTP/1.1 405 Method Not Allowed
Allow: POST, PUT, PATCH
```

Method names are case-insensitive and are always normalized to uppercase in the `Allow` header.

---

## CLI

When no HTTP request exists, `b8_method()` returns:

```text
CLI
```

This allows the helper to be used safely in command-line scripts.

---

## Validation

Request helpers do not validate input.

Example:

```php
$id = (int) b8_get('id', 0);

if ($id < 1) {
    b8_status(400);
    exit();
}
```

Applications should always validate user input before using it.

---

## Design Philosophy

Base8 intentionally keeps request handling simple.

The framework provides direct access to request data without introducing request objects, wrappers, or dependency injection.