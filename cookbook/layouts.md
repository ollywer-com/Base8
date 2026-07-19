# Layouts

This guide demonstrates how to create reusable page layouts.

---

# Project Structure

Create the following structure.

```text
app/

├── modules/
│   └── index.php
│
└── views/
    ├── layout.php
    ├── header.php
    ├── footer.php
    └── home.php
```

---

# Create the Header

Create:

```text
app/views/header.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title><?= htmlspecialchars($title) ?></title>

</head>

<body>

<header>

    <h1>Base8 Demo</h1>

    <hr>

</header>
```

---

# Create the Footer

Create:

```text
app/views/footer.php
```

```php
<footer>

    <hr>

    <small>Copyright © 2026</small>

</footer>

</body>

</html>
```

---

# Create the Content View

Create:

```text
app/views/home.php
```

```php
<h2>Home</h2>

<p>Welcome to Base8.</p>
```

---

# Create the Layout

Create:

```text
app/views/layout.php
```

```php
<?php

require b8_root() . '/views/header.php';

require b8_root() . "/views/{$view}.php";

require b8_root() . '/views/footer.php';
```

---

# Update the Module

Create:

```text
app/modules/index.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    b8_view(
        'layout',
        [
            'title' => 'Home',
            'view'  => 'home'
        ]
    );
}
```

---

# Run the Application

Open:

```text
http://localhost/
```

Expected result:

```text
+--------------------------+

Base8 Demo

----------------------------

Home

Welcome to Base8.

----------------------------

Copyright © 2026

+--------------------------+
```

---

# Reusing the Layout

Create another view.

```text
app/views/about.php
```

```php
<h2>About</h2>

<p>About this application.</p>
```

Create another action.

```php
function about(): void
{
    b8_view(
        'layout',
        [
            'title' => 'About',
            'view'  => 'about'
        ]
    );
}
```

Open:

```text
http://localhost/index/about
```

The same layout is reused while only the page content changes.

---

# Summary

In this guide you learned how to:

- create a reusable layout
- separate header and footer
- reuse the same layout for multiple pages
- keep page content independent from page structure