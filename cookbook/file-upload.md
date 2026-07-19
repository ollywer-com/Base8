# File Upload

This guide demonstrates how to upload a file using Base8.

The framework relies on PHP's native file upload functionality.

---

# Project Structure

Create the following files.

```text
app/

├── modules/
│   └── upload.php
│
└── views/
    └── upload.php

storage/

└── uploads/
```

Uploaded files are stored outside the public directory.

---

# Create the View

Create:

```text
app/views/upload.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>File Upload</title>

</head>

<body>

<h1>File Upload</h1>

<form
    method="post"
    enctype="multipart/form-data"
>

    <input
        type="file"
        name="file"
    >

    <br><br>

    <button type="submit">

        Upload

    </button>

</form>

</body>

</html>
```

---

# Create the Module

Create:

```text
app/modules/upload.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    if (!b8_method('POST')) {

        b8_view('upload');

    }

    if (!isset($_FILES['file'])) {

        b8_status(400);

        exit();

    }

    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {

        b8_status(400);

        exit();

    }

    $extension = strtolower(
        pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        )
    );

    $allowed = [

        'jpg',
        'jpeg',
        'png',
        'gif',
        'pdf'

    ];

    if (!in_array($extension, $allowed, true)) {

        b8_status(400);

        exit();

    }

    $filename = bin2hex(
        random_bytes(16)
    ) . '.' . $extension;

    $destination =
        dirname(b8_root()) .
        '/storage/uploads/' .
        $filename;

    if (!move_uploaded_file(
        $file['tmp_name'],
        $destination
    )) {

        b8_status(500);

        exit();

    }

    b8_redirect('/upload');
}
```

---

# Run the Application

Open:

```text
GET /upload
```

Select a file and click **Upload**.

After a successful upload the application redirects back to the upload page.

---

# Security

Always validate uploaded files.

At a minimum, verify:

- upload errors
- maximum file size
- allowed file extensions
- MIME type

Never trust the original filename.

Generate a random filename instead.

```php
$filename = bin2hex(
    random_bytes(16)
) . '.' . $extension;
```

Always use `move_uploaded_file()`.

Store uploaded files outside the public directory whenever possible.

---

# Best Practices

A production application should:

- limit the maximum upload size
- validate the MIME type
- validate the file extension
- generate random filenames
- store uploaded files outside the public directory
- scan uploaded files when appropriate
- never allow executable files to be uploaded

Private files should be served through the application after authorization instead of being accessed directly from the web server.

---

# Summary

In this guide you learned how to:

- create an upload form
- receive uploaded files
- validate uploaded files
- generate secure filenames
- store uploaded files securely
- apply upload security best practices