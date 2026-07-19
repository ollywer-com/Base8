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
    if (!b8_method('POST')) {

        b8_status(405);

        exit();

    }

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

# Summary

In this guide you learned how to:

- organize API endpoints
- return JSON data
- receive route parameters
- process POST requests
- return appropriate HTTP status codes