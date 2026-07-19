# Base8

> **Lightweight PHP 8 framework.**
>
> **One build. One framework file. One starter package.**

**PHP 8+ • Single File • No Composer Required • Zero Runtime Dependencies • MIT License**

Base8 is a lightweight PHP framework designed for developers who prefer clean, predictable code over unnecessary abstraction.

It stays close to native PHP, follows convention over configuration, and provides everything needed to build modern web applications without hidden behavior or runtime dependencies.

---

## Why Base8?

- Lightweight and fast
- Single-file framework
- Zero runtime dependencies
- No Composer required
- Convention-based routing
- AJAX-first architecture
- Function-based modules
- Small and predictable kernel
- Close to native PHP
- Easy to learn
- Easy to deploy

---

## Philosophy

Base8 follows a few simple principles.

- Stay close to native PHP.
- Convention over configuration.
- Performance first.
- Simplicity first.
- AJAX-first.
- Secure by default.
- No magic.
- No hidden behavior.
- Keep the kernel small and predictable.

---

# Quick Start

Download the latest **Base8 Starter** from the GitHub **Releases** page.

Extract the archive.

Your project will look like this:

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

Create your first module.

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Hello Base8!';
}
```

Open:

```text
http://localhost/
```

You should see:

```text
Hello Base8!
```

Complete installation instructions, including Apache, Nginx, and IIS configuration, are available in **installation.md**.

---

# Routing

| URL | Module | Function |
|------|--------|----------|
| `/` | `index.php` | `index()` |
| `/users` | `users.php` | `index()` |
| `/users/login` | `users.php` | `login()` |
| `/users/profile/15` | `users.php` | `profile('15')` |

Hyphenated action names are automatically converted to camelCase.

---

# Rendering Views

```php
b8_view('users/table');

b8_view('users/row', [
    'user' => $user
]);
```

Views can render complete pages or reusable HTML fragments.

---

# Responses

```php
b8_redirect('/login');

b8_json([
    'success' => true
]);

b8_status(404);
```

---

# Cookies

```php
b8_cookie_set('theme', 'dark');

$theme = b8_cookie_get('theme');

b8_cookie_delete('theme');
```

---

# Sessions

```php
b8_session_set('user_id', 15);

$id = b8_session_get('user_id');

b8_session_delete('user_id');

b8_session_destroy();
```

Sessions start automatically when required.

---

# Error Pages

Supported custom error pages:

```text
404.php
405.php
414.php
500.php
```

If the corresponding file exists in `app/errors`, Base8 renders it automatically.

---

# Build

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
framework/
    Base8.php
```

---

# Documentation

Start here if you're new to Base8.

1. 📦 Installation
2. 🚀 Routing
3. 📥 Requests
4. 📤 Responses
5. 🖼️ Views

Core Features

- 🍪 Cookies
- 🔐 Sessions
- 🔒 Cryptography

Read the documentation:

- [Installation](docs/installation.md)
- [Routing](docs/routing.md)
- [Requests](docs/requests.md)
- [Responses](docs/responses.md)
- [Views](docs/views.md)
- [Cookies](docs/cookies.md)
- [Sessions](docs/sessions.md)
- [Cryptography](docs/crypto.md)

---

# Author

**Oliver Marković**

https://ollywer.com

---

# License

Released under the MIT License.