# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

---

## [1.2.0] - 2026-08-17

### Added

- Module guard hook `_before()`, called before the action
- `b8_method_allow()` request helper, sends an `Allow` header and 405
- `b8_error()` response helper, renders a page from `app/errors/`
- CSRF helpers: `b8_csrf_token()`, `b8_csrf_field()`, `b8_csrf_verify()`,
  `b8_csrf_require()`
- CSRF documentation
- Cookbook guide on protecting endpoints with a bearer token
- Cookbook section on protecting a JSON API

### Changed

- `Base8::error()` is now public, so applications can render error pages

### Fixed

- Documented 405 handling now exists; previously no code path produced it
- `public/.htaccess` now forwards the `Authorization` header to PHP; Apache
  withholds it by default, so bearer tokens never reached the application

### Security

- Module guards run even for unknown actions, so a protected module does not
  reveal which actions it defines
- Session-bound CSRF tokens from `random_bytes()`, compared with `hash_equals()`

---

## [1.1.1] - 2026-07-19

### Added

- Minimal HTTP kernel
- Convention-based routing
- Function-based modules
- Request helpers
- Response helpers
- View helpers
- Cookie helpers
- Session helpers
- Crypto helpers
- Custom error pages
- Comprehensive PHPDoc documentation
- Framework builder
- Project documentation
- Cookbook with practical examples

### Changed

- Improved routing behavior
- Improved helper consistency
- Improved framework documentation
- Improved build process

### Security

- AES-256-CBC encryption helper
- HMAC verification
- Secure cookie defaults
- Strict session configuration
- HTTPS-aware cookie handling
- Path validation