# Contact Form

This guide demonstrates how to process an HTML form using Base8.

---

# Project Structure

Create the following files.

```text
app/

├── modules/
│   └── contact.php
│
└── views/
    └── contact.php
```

---

# Create the View

Create:

```text
app/views/contact.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>Contact</title>

</head>

<body>

<h1>Contact</h1>

<form method="post">

    <label>Name</label><br>

    <input
        type="text"
        name="name"
    >

    <br><br>

    <label>Email</label><br>

    <input
        type="email"
        name="email"
    >

    <br><br>

    <label>Message</label><br>

    <textarea
        name="message"
        rows="6"
        cols="50"
    ></textarea>

    <br><br>

    <button type="submit">

        Send

    </button>

</form>

</body>

</html>
```

---

# Create the Module

Create:

```text
app/modules/contact.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    if (b8_method('POST')) {

        $name = trim(
            b8_post('name', '')
        );

        $email = trim(
            b8_post('email', '')
        );

        $message = trim(
            b8_post('message', '')
        );

        if (
            $name === '' ||
            $email === '' ||
            $message === ''
        ) {
            echo 'All fields are required.';
            exit();
        }

        echo 'Message sent successfully.';
        exit();
    }

    b8_view('contact');
}
```

---

# Run the Application

Open:

```text
http://localhost/contact
```

Fill out the form and click **Send**.

Expected output:

```text
Message sent successfully.
```

---

# Improving Validation

Base8 returns raw request values.

Applications should always validate user input.

Example:

```php
if (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {
    echo 'Invalid email address.';
    exit();
}
```

---

# Summary

In this guide you learned how to:

- create an HTML form
- process POST requests
- retrieve form data
- validate user input
- render a form using a view