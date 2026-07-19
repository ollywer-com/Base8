# Routing

Base8 uses convention-based routing.

Routes are determined automatically from the request URL. No routing configuration is required.

---

## Route Format

```text
/module/action/parameter1/parameter2/...
```

The first URL segment determines the module.

The second URL segment determines the action.

Any remaining segments are passed to the action as function arguments.

---

## Default Route

```text
/
```

Loads:

```text
app/modules/index.php
```

and calls:

```php
index();
```

---

## Module Routing

```text
/users
```

Loads:

```text
app/modules/users.php
```

and calls:

```php
index();
```

---

## Action Routing

```text
/users/login
```

Loads:

```text
app/modules/users.php
```

and calls:

```php
login();
```

---

## Route Parameters

Additional URL segments are passed directly to the action.

URL:

```text
/users/profile/15
```

Module:

```php
function profile(string $id): void
{
    echo $id;
}
```

Output:

```text
15
```

Multiple parameters:

```text
/products/show/15/reviews/2
```

```php
function show(
    string $id,
    string $section,
    string $page
): void
{
}
```

Arguments:

```text
$id      = "15"
$section = "reviews"
$page    = "2"
```

---

## Hyphenated Actions

Hyphenated action names are automatically converted to camelCase.

URL:

```text
/users/change-password
```

Calls:

```php
changePassword();
```

Another example:

```text
/orders/create-invoice
```

Calls:

```php
createInvoice();
```

Module names are never converted.

---

## Valid Route Names

Module and action names may contain:

- letters
- numbers
- hyphens

Examples:

```text
users
blog-posts
api
api2
user-profile
```

---

## Invalid Route Names

The following characters are not allowed:

```text
/
\
.
_
%
?
&
#
```

Names beginning with an underscore are also rejected.

Invalid examples:

```text
_admin
users.php
../users
user profile
```

Requests containing invalid route names return:

```text
404 Not Found
```

---

## Missing Modules

If the requested module does not exist:

```text
/products
```

and

```text
app/modules/products.php
```

does not exist, Base8 returns:

```text
404 Not Found
```

---

## Missing Actions

If the requested action does not exist:

```text
/users/logout
```

and

```php
logout()
```

does not exist, Base8 returns:

```text
404 Not Found
```

---

## URL Length

Very long URLs are rejected automatically.

Maximum URL length:

```text
2048 characters
```

Longer requests return:

```text
414 Request-URI Too Long
```

---

## Design Philosophy

Base8 intentionally avoids routing configuration.

There are:

- no route definitions
- no route attributes
- no annotations
- no regular expressions
- no route cache

Every URL maps directly to a module and an action using the same predictable convention.