# Installation

This guide explains how to get started with the Base8 Starter package.

## Requirements

- PHP 8.0 or newer
- One of the following web servers:
    - Apache (mod_rewrite)
    - Nginx
    - Microsoft IIS (URL Rewrite)

---

## Getting Started

Download the latest **Base8 Starter** package from the GitHub Releases page.

Extract the archive to your preferred location.

The starter package already contains the Base8 framework, application structure, and public directory.

---

## Project Structure

A typical Base8 Starter project looks like this:

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

The `framework` directory may have any name and may be located anywhere outside the public directory.

The web server DocumentRoot must point to the `public` directory.

Depending on your web server, create the appropriate configuration:

- Apache: `.htaccess`
- Nginx: server configuration
- IIS: `web.config`

---

## Front Controller

Create `public/index.php`.

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../framework/Base8.php';

Base8\Base8::run(__DIR__);
```

Base8 automatically locates the application directory.

---

## Apache Configuration

Create `public/.htaccess`.

```apache
<IfModule mod_rewrite.c>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^ index.php [QSA,L]

</IfModule>
```

Enable `mod_rewrite` if it is not already enabled.

---

## Nginx Configuration

Example server configuration:

```nginx
server {

    listen 80;

    server_name localhost;

    root /path/to/project/public;

    index index.php;

    location / {

        try_files $uri $uri/ /index.php?$query_string;

    }

    location ~ \.php$ {

        include fastcgi_params;

        fastcgi_pass unix:/run/php/php8.4-fpm.sock;

        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

    }

}
```

The `root` directive must point to the `public` directory.

Adjust the PHP-FPM socket or host according to your environment.

---

## IIS Configuration

Create `public/web.config`.

```xml
<?xml version="1.0" encoding="utf-8"?>

<configuration>

    <system.webServer>

        <rewrite>

            <rules>

                <rule name="Base8" stopProcessing="true">

                    <match url=".*" />

                    <conditions logicalGrouping="MatchAll">

                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />

                    </conditions>

                    <action type="Rewrite" url="index.php" />

                </rule>

            </rules>

        </rewrite>

    </system.webServer>

</configuration>
```

Install the IIS URL Rewrite Module before using this configuration.

---

## First Module

Create:

```text
app/modules/index.php
```

```php
<?php

declare(strict_types=1);

function index(): void
{
    echo 'Hello Base8!';
}
```

Open your browser:

```text
http://localhost/
```

You should see:

```text
Hello Base8!
```

---

## Application Structure

The application consists of three directories.

```text
app/

├── errors/
├── modules/
└── views/
```

### modules/

Contains application modules.

Example:

```text
modules/

index.php
users.php
products.php
```

---

### views/

Contains PHP views and reusable HTML fragments.

Example:

```text
views/

layout.php
users/

    list.php
    row.php
```

---

### errors/

Contains custom error pages.

Supported pages:

```text
404.php
405.php
414.php
500.php
```

If a matching file exists, Base8 renders it automatically.

---

## Build

Generate the production framework file.

Command line:

```text
php build.php
```

or open:

```text
http://localhost/project/build.php
```

The build process generates:

```text
framework/

    Base8.php
```

The generated framework file is included in every Base8 Starter release.

---

## Next Steps

Continue with:

- requests.md
- routing.md
- responses.md
- views.md
- cookies.md
- sessions.md
- crypto.md