# Base8

Base8 is a lightweight, AJAX-first PHP 8 framework designed for simplicity, performance, and predictable behavior.

It provides a minimal HTTP kernel, convention-based routing, and a small collection of helper functions while staying close to native PHP.

## Features

- PHP 8+
- Single-file framework
- Convention-based routing
- Function-based modules
- AJAX-first architecture
- JSON and HTML fragment responses
- Request helpers
- Response helpers
- View helpers
- Cookie helpers
- Session helpers
- Zero dependencies

## Requirements

- PHP 8.0 or newer
- Apache with mod_rewrite enabled

## Philosophy

- Performance first.
- Simplicity first.
- Convention over configuration.
- Use PHP, do not fight PHP.
- Use the browser, do not fight the browser.
- AJAX-first.
- Secure by default.
- Keep the kernel small and predictable.


## Project Structure

```text
project/

├── app/
│   ├── modules/
│   ├── views/
│   └── errors/
│
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   ├── images/
│   └── ...
│
└── Base8.php
```

`Base8.php` may be located anywhere. Adjust the `require` path accordingly. The web server DocumentRoot must point to `public/`.

## Installation

Create `public/index.php`.

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../framework/Base8.php';

Base8\Base8::run(__DIR__);
```

Create `public/.htaccess`.

```apache
<IfModule mod_rewrite.c>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^ index.php [QSA,L]

</IfModule>
```

Configure your web server so its DocumentRoot points to the `public` directory.

## First Module

Create `app/modules/index.php`.

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Hello Base8!';
}
```

## Routing

| URL | Module | Function |
| --- | --- | --- |
| `/` | `index.php` | `index()` |
| `/users` | `users.php` | `index()` |
| `/users/login` | `users.php` | `login()` |
| `/users/profile/15` | `users.php` | `profile('15')` |

Hyphenated action names are automatically converted to camelCase.

## Views

```php
b8_view('users/table');
```

```php
b8_view('users/row', ['user' => $user]);
```

Views can render complete pages or reusable HTML fragments for AJAX responses.

## Responses

```php
b8_redirect('/login');
b8_json(['success' => true]);
b8_status(404);
```

## Cookies

```php
b8_cookie_set('theme', 'dark');
$theme = b8_cookie_get('theme');
b8_cookie_delete('theme');
```

## Sessions

```php
b8_session_set('user_id', 15);
$id = b8_session_get('user_id');
b8_session_delete('user_id');
b8_session_destroy();
```

Sessions start automatically when required.

## Error Pages

Default framework pages:

```text
404
405
414
500
```

The framework automatically returns the appropriate HTTP status code. If a matching error page exists in `app/errors`, it is rendered.

## Build


```text
php build.php
```

or open:

```text
http://localhost/project/build.php
```

## Author

**Oliver Marković**

Website: https://ollywer.com

## License

Released under the MIT License.
