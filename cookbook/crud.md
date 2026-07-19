# CRUD

This guide demonstrates how to implement a simple CRUD application using Base8.

The example manages a collection of users stored in memory to keep the focus on the framework rather than database access.

---

# Project Structure

Create the following files.

```text
app/

├── modules/
│   └── users.php
│
└── views/
    ├── users-list.php
    ├── users-create.php
    └── users-edit.php
```

---

# Create the Module

Create:

```text
app/modules/users.php
```

```php
<?php

declare(strict_types=1);

function users(): array
{
    return [

        1 => 'John Doe',

        2 => 'Jane Smith',

        3 => 'Oliver'

    ];
}

function index(): void
{
    b8_view(
        'users-list',
        [
            'users' => users()
        ]
    );
}

function create(): void
{
    if (b8_method('POST')) {

        $name = trim(
            b8_post('name', '')
        );

        if ($name === '') {

            echo 'Name is required.';

            exit();

        }

        b8_redirect('/users');
    }

    b8_view('users-create');
}

function edit(string $id): void
{
    $users = users();

    if (!isset($users[$id])) {

        b8_status(404);

        exit();

    }

    if (b8_method('POST')) {

        $name = trim(
            b8_post('name', '')
        );

        if ($name === '') {

            echo 'Name is required.';

            exit();

        }

        b8_redirect('/users');
    }

    b8_view(
        'users-edit',
        [
            'id' => $id,
            'name' => $users[$id]
        ]
    );
}

function delete(string $id): void
{
    $users = users();

    if (!isset($users[$id])) {

        b8_status(404);

        exit();

    }

    b8_redirect('/users');
}
```

---

# Create the List View

Create:

```text
app/views/users-list.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>Users</title>

</head>

<body>

<h1>Users</h1>

<p>

    <a href="/users/create">

        Create User

    </a>

</p>

<ul>

<?php foreach ($users as $id => $name): ?>

    <li>

        <?= htmlspecialchars($name) ?>

        [

        <a href="/users/edit/<?= $id ?>">

            Edit

        </a>

        |

        <a href="/users/delete/<?= $id ?>">

            Delete

        </a>

        ]

    </li>

<?php endforeach; ?>

</ul>

</body>

</html>
```

---

# Create the Create View

Create:

```text
app/views/users-create.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>Create User</title>

</head>

<body>

<h1>Create User</h1>

<form method="post">

    <input
        type="text"
        name="name"
        placeholder="Name"
    >

    <br><br>

    <button type="submit">

        Save

    </button>

</form>

</body>

</html>
```

---

# Create the Edit View

Create:

```text
app/views/users-edit.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>Edit User</title>

</head>

<body>

<h1>Edit User</h1>

<form method="post">

    <input
        type="text"
        name="name"
        value="<?= htmlspecialchars($name) ?>"
    >

    <br><br>

    <button type="submit">

        Save

    </button>

</form>

</body>

</html>
```

---

# List Users

Open:

```text
GET /users
```

The application displays the user list.

---

# Create a User

Open:

```text
GET /users/create
```

Fill in the form and click **Save**.

The application redirects back to the list.

---

# Edit a User

Open:

```text
GET /users/edit/1
```

Update the user name and click **Save**.

The application redirects back to the list.

---

# Delete a User

Open:

```text
GET /users/delete/1
```

The application redirects back to the list.

In a real application this action would remove the record from the database.

---

# Replacing the Data Source

This example uses an in-memory array to keep the code focused on Base8.

In a real application, replace the `users()` function with database queries.

The rest of the module remains almost unchanged.

---

# Summary

In this guide you learned how to:

- list records
- create a record
- edit a record
- delete a record
- validate form input
- redirect after successful actions