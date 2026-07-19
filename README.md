# Base8

Base8 is a lightweight PHP 8 framework built around a simple idea:

> **One build. One framework file. One starter package.**

It stays close to native PHP, avoids unnecessary abstraction, and provides predictable behavior with zero runtime dependencies.

## Features

- PHP 8+
- Single-file framework
- Zero runtime dependencies
- Convention-based routing
- Function-based modules
- AJAX-first architecture
- JSON and HTML fragment responses
- Request helpers
- Response helpers
- View helpers
- Cookie helpers
- Session helpers
- Small and predictable kernel

---

## Philosophy

Base8 is designed around a few simple principles.

- Stay close to native PHP.
- Convention over configuration.
- Performance first.
- Simplicity first.
- AJAX-first.
- Secure by default.
- Keep the kernel small and predictable.
- No magic.
- No hidden behavior.

---

## Requirements

- PHP 8.0 or newer
- One of the following web servers:
    - Apache (mod_rewrite)
    - Nginx
    - Microsoft IIS (URL Rewrite)

---

## Quick Start

Download the latest **Base8 Starter** from the **Releases** page.

The starter package already contains everything required to begin development.

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
    ├── css/
    ├── images/
    ├── js/
    ├── favicon.ico
    ├── index.php
    └── robots.txt
```

Point your web server DocumentRoot to the `public` directory.

Create `public/index.php`.

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../framework/Base8.php';

Base8\Base8::run(__DIR__);
```

See **installation.md** for complete Apache, Nginx, and IIS configuration examples.

---

## Routing

| URL | Module | Function |
| --- | --- | --- |
| `/` | `index.php` | `index()` |
| `/users` | `users.php` | `index()` |
| `/users/login` | `users.php` | `login()` |
| `/users/profile/15` | `users.php` | `profile('15')` |

Hyphenated action names are automatically converted to camelCase.

---

## Views

```php
b8_view('users/table');

b8_view('users/row', [
    'user' => $user
]);
```

Views can render complete pages or reusable HTML fragments.

---

## Responses

```php
b8_redirect('/login');

b8_json([
    'success' => true
]);

b8_status(404);
```

---

## Cookies

```php
b8_cookie_set('theme', 'dark');

$theme = b8_cookie_get('theme');

b8_cookie_delete('theme');
```

---

## Sessions

```php
b8_session_set('user_id', 15);

$id = b8_session_get('user_id');

b8_session_delete('user_id');

b8_session_destroy();
```

Sessions start automatically when required.

---

## Error Pages

Supported custom error pages:

```text
404.php
405.php
414.php
500.php
```

If a matching file exists in `app/errors`, Base8 renders it automatically.

---

## Build

Generate the production framework file.

```text
php build.php
```

or open:

```text
http://localhost/project/build.php
```

The generated framework is written to:

```text
framework/Base8.php
```

---

## Documentation

- installation.md
- routing.md

---

## Author

**Oliver Marković**

https://ollywer.com

---

## License

Released under the MIT License.