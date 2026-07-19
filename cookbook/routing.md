# Routing

This guide demonstrates how Base8 maps URLs to modules and actions.

---

# Project Structure

Create the following module.

```text
app/

└── modules/

    index.php
    users.php
```

---

# Default Route

Create:

```text
app/modules/index.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Home page';
}
```

Open:

```text
http://localhost/
```

Expected output:

```text
Home page
```

---

# Module Routing

Create:

```text
app/modules/users.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Users';
}
```

Open:

```text
http://localhost/users
```

Expected output:

```text
Users
```

---

# Action Routing

Update the module.

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Users';
}

function profile(): void
{
    echo 'User profile';
}
```

Open:

```text
http://localhost/users/profile
```

Expected output:

```text
User profile
```

---

# Route Parameters

Update the module.

```php
<?php

declare(strict_types=1);

function profile(string $id): void
{
    echo "User ID: {$id}";
}
```

Open:

```text
http://localhost/users/profile/15
```

Expected output:

```text
User ID: 15
```

---

# Multiple Parameters

```php
<?php

declare(strict_types=1);

function order(
    string $userId,
    string $orderId
): void
{
    echo "User: {$userId}, Order: {$orderId}";
}
```

Open:

```text
http://localhost/users/order/15/125
```

Expected output:

```text
User: 15, Order: 125
```

---

# Hyphenated Actions

Update the module.

```php
<?php

declare(strict_types=1);

function changePassword(): void
{
    echo 'Change password';
}
```

Open:

```text
http://localhost/users/change-password
```

Expected output:

```text
Change password
```

Base8 automatically converts hyphenated action names to camelCase.

---

# Missing Module

Open:

```text
http://localhost/products
```

Expected result:

```text
404 Not Found
```

---

# Missing Action

Open:

```text
http://localhost/users/delete
```

Expected result:

```text
404 Not Found
```

---

# Summary

In this guide you learned how to:

- create modules
- create actions
- receive route parameters
- use hyphenated action names
- understand how Base8 resolves URLs