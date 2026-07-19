# Contributing

Thank you for your interest in contributing to Base8.

The goal of the project is to remain small, predictable, and close to native PHP. Every contribution should follow this philosophy.

---

# Before You Start

Before submitting a contribution, please:

- read the documentation
- read the Cookbook
- follow the coding standards
- verify that the proposed change fits the project's philosophy

---

# Project Philosophy

Base8 follows a few simple principles.

- Performance first.
- Simplicity first.
- Convention over configuration.
- Use PHP, do not fight PHP.
- Keep the kernel small.
- Avoid unnecessary abstractions.
- Prefer native PHP whenever possible.

---

# Coding Standards

Please follow the project's coding standards.

In particular:

- use four spaces for indentation
- never use tabs
- always use `declare(strict_types=1);`
- write PHPDoc for public APIs
- keep functions small and focused
- prefer readability over clever code

---

# Helper Design

Before adding a new helper, ask the following questions.

- Does it simplify native PHP?
- Does it improve security?
- Does it significantly improve readability?

If the answer is **no**, the helper probably does not belong in Base8.

The framework intentionally avoids helper functions that only replace one or two obvious PHP statements.

---

# Kernel

The HTTP kernel should remain as small as possible.

Avoid adding features that belong in the application instead of the framework.

---

# Dependencies

Base8 has zero runtime dependencies.

Do not introduce Composer packages or third-party libraries into the framework.

Applications are free to use any library they need.

---

# Build

Base8 is distributed as a single file.

After modifying the source code, rebuild the framework.

```text
php build.php
```

or open:

```text
http://localhost/project/build.php
```

Always verify that the generated `Base8.php` file builds successfully.

---

# Pull Requests

Before opening a Pull Request:

- ensure the framework builds successfully
- verify that existing functionality still works
- update the documentation when necessary
- keep Pull Requests focused on a single topic

---

# Documentation

Every public API should be documented.

When adding a new feature, update the appropriate documentation and Cookbook examples if applicable.

---

# Thank You

Thank you for helping improve Base8.