# Pagination

This guide demonstrates one way to implement pagination in a Base8 application.

The example uses an in-memory array to keep the focus on Base8 rather than database access.

---

# Project Structure

Create the following files.

```text
app/

├── modules/
│   └── products.php
│
└── views/
    └── products.php
```

---

# Create the Module

Create:

```text
app/modules/products.php
```

```php
<?php

declare(strict_types=1);

function products(): array
{
    return range(1, 100);
}

function index(): void
{
    $page = max(
        1,
        (int) b8_get('page', 1)
    );

    $perPage = 10;

    $products = products();

    $total = count($products);

    $pages = (int) ceil(
        $total / $perPage
    );

    $offset = ($page - 1) * $perPage;

    b8_view(
        'products',
        [
            'products' => array_slice(
                $products,
                $offset,
                $perPage
            ),
            'page' => $page,
            'pages' => $pages
        ]
    );
}
```

---

# Create the View

Create:

```text
app/views/products.php
```

```php
<!DOCTYPE html>

<html>

<head>

    <title>Products</title>

</head>

<body>

<h1>Products</h1>

<ul>

<?php foreach ($products as $product): ?>

    <li>

        Product <?= $product ?>

    </li>

<?php endforeach; ?>

</ul>

<hr>

<?php if ($page > 1): ?>

<a href="?page=<?= $page - 1 ?>">

    Previous

</a>

<?php endif; ?>

Page <?= $page ?> of <?= $pages ?>

<?php if ($page < $pages): ?>

<a href="?page=<?= $page + 1 ?>">

    Next

</a>

<?php endif; ?>

</body>

</html>
```

---

# Run the Application

Open:

```text
GET /products
```

Expected result:

```text
Products 1-10
```

Open:

```text
GET /products?page=2
```

Expected result:

```text
Products 11-20
```

Open:

```text
GET /products?page=5
```

Expected result:

```text
Products 41-50
```

---

# Database Pagination

In a real application, pagination is usually performed by the database.

Example:

```sql
SELECT *

FROM products

LIMIT 10 OFFSET 20;
```

Only the data source changes.

The pagination logic remains the same.

---

# Summary

In this guide you learned how to:

- retrieve the current page
- calculate page offsets
- split data into pages
- generate Previous and Next links