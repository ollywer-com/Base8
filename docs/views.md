# Views

Base8 uses native PHP views.

Views are simple PHP files located in the application's `views` directory. No template engine is required.

---

## Rendering a View

Use `b8_view()` to render a view.

```php
b8_view('users/list');
```

This loads:

```text
app/views/users/list.php
```

The current request is terminated after the view is rendered.

---

## Passing Data

Variables may be passed to the view using the second argument.

```php
b8_view(
    'users/profile',
    [
        'user' => $user,
        'posts' => $posts
    ]
);
```

Inside the view:

```php
<h1><?= $user['name'] ?></h1>

<p>Total posts: <?= count($posts) ?></p>
```

Each array key becomes a local variable.

---

## View Structure

Example:

```text
app/

└── views/

    layout.php

    users/
        list.php
        row.php
        profile.php

    products/
        table.php
```

Views may be organized into subdirectories.

---

## HTML Fragments

Views are commonly used to return reusable HTML fragments.

Example:

```php
b8_view(
    'users/row',
    [
        'user' => $user
    ]
);
```

This approach works especially well for AJAX responses.

---

## Complete Pages

Views may also render complete HTML pages.

Example:

```php
<!DOCTYPE html>

<html>

<head>
    <title>Home</title>
</head>

<body>

<h1>Welcome</h1>

</body>

</html>
```

Base8 does not distinguish between pages and partial views.

---

## View Names

Always omit the `.php` extension.

Correct:

```php
b8_view('users/list');
```

Incorrect:

```php
b8_view('users/list.php');
```

---

## Security

Base8 prevents access to invalid view paths.

The following are rejected automatically:

```text
..
\
NUL byte
```

If an invalid view name is supplied, the framework returns:

```text
404 Not Found
```

---

## Variable Extraction

View data is extracted using:

```php
extract($data, EXTR_SKIP);
```

Existing variables are never overwritten.

---

## Example

Module:

```php
function profile(): void
{
    $user = [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ];

    b8_view(
        'users/profile',
        [
            'user' => $user
        ]
    );
}
```

View:

```php
<h1><?= $user['name'] ?></h1>

<p><?= $user['email'] ?></p>
```

---

## Design Philosophy

Base8 intentionally uses native PHP for views.

There are:

- no template engine
- no custom syntax
- no compiled templates
- no view inheritance
- no components

If you know PHP, you already know how to write Base8 views.