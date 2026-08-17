# JSON API

This guide demonstrates how to build a simple REST-style JSON API using Base8.

---

# Project Structure

Create the following module.

```text
app/

└── modules/

    api.php
```

---

# Create the Module

Create:

```text
app/modules/api.php
```

```php
<?php

declare(strict_types=1);

function users(): void
{
    $users = [

        [
            'id' => 1,
            'name' => 'John Doe'
        ],

        [
            'id' => 2,
            'name' => 'Jane Smith'
        ],

        [
            'id' => 3,
            'name' => 'Oliver'
        ]

    ];

    b8_json($users);
}

function user(string $id): void
{
    $user = [

        'id' => (int) $id,
        'name' => 'Oliver',
        'email' => 'oliver@example.com'

    ];

    b8_json($user);
}

function createUser(): void
{
    b8_method_allow('POST');

    $name = trim(
        b8_post('name', '')
    );

    $email = trim(
        b8_post('email', '')
    );

    if (
        $name === '' ||
        $email === ''
    ) {

        b8_json(
            [
                'success' => false,
                'message' => 'All fields are required.'
            ],
            400
        );

    }

    /*
     * Save the user...
     */

    b8_json([
        'success' => true,
        'message' => 'User created successfully.'
    ]);
}
```

---

# List All Users

Open:

```text
GET /api/users
```

Expected response:

```json
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {
        "id": 2,
        "name": "Jane Smith"
    },
    {
        "id": 3,
        "name": "Oliver"
    }
]
```

---

# Retrieve a Single User

Open:

```text
GET /api/user/3
```

Expected response:

```json
{
    "id": 3,
    "name": "Oliver",
    "email": "oliver@example.com"
}
```

---

# Create a User

Send a POST request.

```http
POST /api/create-user
```

Form data:

```text
name=Oliver
email=oliver@example.com
```

Expected response:

```json
{
    "success": true,
    "message": "User created successfully."
}
```

---

# Invalid Request

If one or more required fields are missing, the API returns:

```http
HTTP/1.1 400 Bad Request
```

```json
{
    "success": false,
    "message": "All fields are required."
}
```

---

# Unsupported Method

`createUser()` calls `b8_method_allow('POST')`, so any other method is rejected before the action runs:

```text
GET /api/create-user
```

```http
HTTP/1.1 405 Method Not Allowed
Allow: POST
```

---

# Protecting the API

The API above is public. Every endpoint answers anyone who asks.

To require a token, declare `_before()` in the module. Base8 calls it before the action, so a single function protects every endpoint at once.

```php
function _before(string $action, array $params): void
{
    if ($action === 'users') {
        return;
    }

    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (
        preg_match('/^Bearer\s+(\S+)$/i', $header, $matches) !== 1 ||
        !hash_equals((string) getenv('API_TOKEN'), $matches[1])
    ) {

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
```

Requests without a valid token now receive:

```http
HTTP/1.1 401 Unauthorized
```

```json
{
    "success": false,
    "message": "Unauthorized."
}
```

A token-authenticated API does not need CSRF protection, because browsers never attach an `Authorization` header automatically. If the same endpoints also accept a session cookie, CSRF protection becomes necessary again.

Apache does not always forward the `Authorization` header to PHP. See **auth.md** for the full guide, including the required `.htaccess` rule.

---

# Summary

In this guide you learned how to:

- organize API endpoints
- return JSON data
- receive route parameters
- process POST requests
- restrict an endpoint to a single HTTP method
- protect endpoints with a module guard
- return appropriate HTTP status codes