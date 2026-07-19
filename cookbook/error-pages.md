# Error Pages

This guide demonstrates how to customize HTTP error pages in Base8.

---

# Project Structure

Create the following files.

```text
app/

└── errors/

    layout.php
    401.php
    403.php
    404.php
    500.php
```

---

# Create the Layout

Create:

```text
app/errors/layout.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title><?= htmlspecialchars($title) ?></title>

</head>

<body>

<h1><?= htmlspecialchars($title) ?></h1>

<hr>

<?php require $content; ?>

<hr>

<small>Base8</small>

</body>

</html>
```

---

# Create a 404 Page

Create:

```text
app/errors/404.php
```

```php
<?php

$title = '404 Not Found';

$content = __DIR__ . '/404.content.php';

require __DIR__ . '/layout.php';
```

Create:

```text
app/errors/404.content.php
```

```php
<p>

The requested page could not be found.

</p>
```

---

# Create a 403 Page

Create:

```text
app/errors/403.php
```

```php
<?php

$title = '403 Forbidden';

$content = __DIR__ . '/403.content.php';

require __DIR__ . '/layout.php';
```

Create:

```text
app/errors/403.content.php
```

```php
<p>

You do not have permission to access this resource.

</p>
```

---

# Create a 500 Page

Create:

```text
app/errors/500.php
```

```php
<?php

$title = '500 Internal Server Error';

$content = __DIR__ . '/500.content.php';

require __DIR__ . '/layout.php';
```

Create:

```text
app/errors/500.content.php
```

```php
<p>

An unexpected error has occurred.

</p>
```

---

# Create a 401 Page

Create:

```text
app/errors/401.php
```

```php
<?php

$title = '401 Unauthorized';

$content = __DIR__ . '/401.content.php';

require __DIR__ . '/layout.php';
```

Create:

```text
app/errors/401.content.php
```

```php
<p>

Authentication is required to access this resource.

</p>
```

---

# Triggering an Error

Example:

```php
b8_status(403);

exit();
```

Base8 automatically loads the corresponding error page.

---

# Summary

In this guide you learned how to:

- customize HTTP error pages
- create a shared error layout
- reuse the same layout for multiple error pages
- return appropriate HTTP status codes