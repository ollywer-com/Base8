# Hello World

This guide demonstrates how to build your first Base8 application.

---

# Project Structure

Create the following directory structure.

```text
project/

├── app/
│   ├── errors/
│   ├── modules/
│   └── views/
│
├── framework/
│   └── Base8.php
│
└── public/
    ├── .htaccess
    └── index.php
```

---

# Create the Front Controller

Create:

```text
public/index.php
```

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../framework/Base8.php';

Base8\Base8::run(__DIR__);
```

This is the application's entry point.

Every request passes through this file.

---

# Create the First Module

Create:

```text
app/modules/index.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Hello World!';
}
```

---

# Run the Application

Open your browser.

```text
http://localhost/
```

Expected output:

```text
Hello World!
```

At this point you have created your first Base8 application.

---

# Rendering a View

Instead of generating HTML directly inside the module, create a view.

Create:

```text
app/views/home.php
```

```php
<!DOCTYPE html>

<html>

<head>
    <title>Base8</title>
</head>

<body>

<h1>Hello World!</h1>

<p>Welcome to Base8.</p>

</body>

</html>
```

Update the module.

```php
<?php

declare(strict_types=1);

function index(): void
{
    b8_view('home');
}
```

Reload the page.

The HTML is now rendered from the view.

---

# Passing Data to a View

Modules may pass data to a view.

```php
<?php

declare(strict_types=1);

function index(): void
{
    b8_view(
        'home',
        [
            'name' => 'Oliver'
        ]
    );
}
```

Update the view.

```php
<!DOCTYPE html>

<html>

<head>
    <title>Base8</title>
</head>

<body>

<h1>Hello <?= htmlspecialchars($name) ?>!</h1>

<p>Welcome to Base8.</p>

</body>

</html>
```

Expected output:

```text
Hello Oliver!
```

---

# Summary

In this guide you learned how to:

- create a Base8 application
- create the front controller
- create a module
- render a view
- pass data to a view

The next cookbook example introduces Base8 routing.