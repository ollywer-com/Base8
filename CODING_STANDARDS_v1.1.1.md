# Base8 Coding Standards

Version: 1.1.0

------------------------------------------------------------------------

# Philosophy

Base8 follows these core principles:

-   Performance first
-   Simplicity first
-   Convention over configuration
-   Use PHP instead of hiding it
-	Use the browser, do not fight the browser
-	AJAX-first
-	Secure by default
-   Keep the kernel small and predictable

Whenever a new feature is proposed, ask:

> Does it follow the Base8 philosophy?

If the answer is **no**, it probably does not belong in Base8.

------------------------------------------------------------------------

# Language

The following must always be written in English:

-   Source code
-   Comments
-   PHPDoc
-   Constants
-   Error messages
-   README
-   Documentation
-   Git commit messages

Discussions between contributors may use any language.

------------------------------------------------------------------------

# General Rules

-   Keep the framework small.
-   Prefer explicit code over hidden behavior.
-   Avoid unnecessary abstractions.
-   Avoid unnecessary classes.
-   Avoid unnecessary methods.
-   Avoid unnecessary object creation.
-   Avoid unnecessary function calls.
-   Every component must have a single responsibility.

------------------------------------------------------------------------

# Framework Structure

The framework is developed inside the `src/` directory.

Example:

``` text
src/
    _.php
    request.php
    response.php
    view.php
    session.php
    cookie.php
```

Rules:

-   `_.php` is always the kernel.
-   `_.php` is always the first file.
-   All helper files are stored directly inside `src/`.
-   Nested directories are not allowed.

------------------------------------------------------------------------

# Build

The build script has only one responsibility:

Concatenate every PHP file from `src/` into a single:

``` text
Base8.php
```

Rules:

-   Never modify source code.
-   Never optimize source code.
-   Never inject code.
-   Never reorder files manually.
-   Files are concatenated alphabetically.
-   `_.php` is loaded first because of its filename.

Special rules:

During concatenation, the builder removes the opening `<?php`
tag from every file except the first one.

The builder also removes the `declare(strict_types=1);`
declaration from every file except the first one.

These are the only allowed source transformations.

Their sole purpose is to produce valid PHP output and they
must never change the behavior of the program.


------------------------------------------------------------------------

# Source Files

Every PHP source file must begin with exactly:

```php
<?php

declare(strict_types=1);
```

Rules:

- No UTF-8 BOM.
- No blank lines before `<?php`.
- No comments before `declare(strict_types=1);`.
- No HTML outside PHP.
- `declare(strict_types=1);` must be the first statement.
- Source files must use LF (`\n`) line endings.

------------------------------------------------------------------------

# Kernel

The kernel is implemented in:

``` text
_.php
```

The kernel exposes one public class:

``` php
namespace Base8;

final class Base8
```

Public API:

```php
Base8::run(string $root);
```

The argument represents the application's public directory.

Kernel responsibilities:

-   Parse the request URI.
-   Validate route segments.
-   Resolve the module.
-   Resolve the action.
-   Execute the action.
-   Handle framework HTTP errors.

The kernel must never contain:

-   Database logic
-   View rendering
-   Session handling
-   Cookies
-   Mail
-   Validation
-   Business logic

------------------------------------------------------------------------

# Method Rules

Do not extract methods only to make code look cleaner.

A private method is allowed only if:

-   it is used more than once;
-   it removes duplicated code;
-   it has one clear responsibility.

Otherwise, keep the code inside `run()`.

------------------------------------------------------------------------

# Routing

-   One request → One module → One action.
-   Routes are resolved by convention.
-   Routes are never registered.
-   Controllers do not exist.


Applications may be installed in any directory.

The web server DocumentRoot must point to the application's public directory.

The kernel must automatically resolve the application directory from the supplied public directory.

No configuration is required.

Application Directories:

- The public directory must be explicitly provided to the kernel.
- The kernel must resolve the application directory from the supplied public directory and load application resources from app/.
- The kernel must never infer arbitrary application locations; the application directory is always ../app relative to the public directory.


Example:

``` text
/users/reset-password
```

↓

``` text
app/modules/users.php
```

↓

``` php
resetPassword();
```

------------------------------------------------------------------------

# URI

Rules:

-   URI uses kebab-case.
-   URI is case-insensitive.
-   Module and action are normalized internally.

Allowed route characters:

-   A-Z
-   a-z
-   0-9
-   Hyphen (-)   

Segments beginning with `_` are reserved.

------------------------------------------------------------------------

# Namespaces

Source files may contain namespace declarations.

`use` statements should be avoided whenever possible.

Framework source files should prefer fully qualified class names for PHP built-in classes and exceptions.

Examples:

```php
throw new \RuntimeException('...');
```

```php
throw new \InvalidArgumentException('...');
```

```php
catch (\Throwable $e) {
}
```

Using fully qualified class names prevents duplicate `use` statements after the framework source files are concatenated into a single `Base8.php` file.

------------------------------------------------------------------------

# Naming

## Module actions

Use camelCase.

``` php
index();
editUser();
resetPassword();
```

## Helper functions

Use snake_case with the `b8_` prefix.

``` php
b8_get();
b8_post();
b8_request();
b8_method();

b8_view();
b8_json();
b8_redirect();
```

## Internal methods

Use camelCase.

``` php
run();
error();
isValidRouteSegment();
```

------------------------------------------------------------------------

# Request Helpers

Base8 never uses `$_REQUEST`.

Request helpers:

``` php
b8_get();
b8_post();
b8_request();
b8_method();
```

`b8_method()`:

-   returns the current HTTP method;
-   or compares it when a method name is supplied.

Examples:

``` php
b8_method();
b8_method('GET');
b8_method('POST');
b8_method('PUT');
b8_method('PATCH');
b8_method('DELETE');
```

------------------------------------------------------------------------

# Modules

Each module is exactly one PHP file.

``` text
app/modules/

index.php
users.php
products.php
```

Nested module directories are not allowed.

------------------------------------------------------------------------

# Application Structure

Minimal application:

``` text
my-app/
│
├── app/
│   ├── modules/
│   ├── views/
│   └── errors/
│
├── public/
│   ├── index.php
│   └── .htaccess
│
└── Base8.php (may be located anywhere)
```

Rules:

- The web server DocumentRoot must point to `public/`.
- `Base8::run(__DIR__)` must receive the path to `public/`.
- The framework resolves the application directory as `../app`.
- Mandatory directories:
  - `app/modules`
  - `app/views`
  - `app/errors`

------------------------------------------------------------------------

# Error Handling

Framework error pages:

``` text
app/errors/
    404.php
    405.php
    414.php
    500.php
```

Rules:

-   Missing module → 404
-   Missing action → 404
-   Unsupported HTTP method → 405
-   URI too long → 414
-   Uncaught exception/fatal error → 500

If an error page does not exist, only the HTTP status code is returned.

------------------------------------------------------------------------

# Security

The kernel is responsible for framework-level security.

It must:

-   validate module names;
-   validate action names;
-   reject invalid route segments;
-   prevent directory traversal;
-   prevent loading arbitrary PHP files;
-   load modules only from `app/modules`;
-	prevent exposing application source code through the web server;

Application security (SQL injection, XSS, CSRF, authentication,
authorization, uploads, etc.) belongs outside the kernel.


------------------------------------------------------------------------

# Documentation

All public framework APIs must be documented.

Rules:

- Every public function must have a PHPDoc block.
- PHPDoc sections should appear in the following order:
  1. Short description.
  2. Additional explanation (optional).
  3. `@param` tags in declaration order.
  4. `@return`.
  5. `@throws` (when applicable).
  6. Usage example (optional).
- Every parameter must be documented using `@param`.
- Every return value must be documented using `@return`.
- Documentation must describe the purpose of each parameter, not only its type.
- Documentation should be IDE-friendly.
- Examples may be added only when they improve clarity.

------------------------------------------------------------------------

# Performance

Performance is a primary design goal.

The kernel should:

-   load exactly one module;
-   execute exactly one action;
-   avoid unnecessary allocations;
-   avoid unnecessary abstractions;
-   avoid unnecessary function calls.

Optimize only when readability is preserved.


------------------------------------------------------------------------

# Git

The repository should contain a `.gitattributes` file to normalize line endings.

Example:

```text
* text=auto eol=lf
*.php text eol=lf
*.md text eol=lf
```

------------------------------------------------------------------------

# Golden Rules

- Performance first.
- Simplicity first.
- Convention over configuration.
- One request → One module → One action.
- Keep the kernel small.
- Use PHP, do not fight PHP.
- Use the browser, do not fight the browser.
- AJAX-first.
- Secure by default.
- If the builder has to think, the design is probably wrong.