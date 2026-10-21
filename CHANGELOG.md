# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

---

## [1.3.0] - 2026-10-21

### Added

- Module fallback hook `_fallback()`. When the requested action does not
  exist and the module declares it, Base8 calls it with the original
  action segment and the route parameters instead of returning 404, so a
  module can serve pages by slug, such as `/page/aims-and-scope`. A real
  action always takes precedence, the guard runs first, and a fallback
  declared outside the module file is ignored.

---

## [1.2.2] - 2026-10-04

### Fixed

- An unknown action in the `index` module returns 404 instead of 500, so a
  mistyped link such as `/index/missing` no longer shows a server error.
  500 is now reserved for a missing `index` module or `index()` action.

---

## [1.2.1] - 2026-10-04

### Added

- Routing tests (`php tests/routing.php`)

### Security

- Only functions declared in the resolved module file are routable as
  actions. Previously any existing function matched, so `/index/phpinfo`
  executed `phpinfo()` and a URL could call any global helper from another
  file whose name matched the segment. Such routes now return 404 (500 for
  the `index` module, as for other missing actions there). The module guard
  still runs first, so unknown actions stay hidden behind it.
- The module guard `_before()` is called only when it is declared in the
  resolved module file. Previously a `_before()` from any other loaded file,
  such as a helper in `app/lib`, ran as the guard of every module.

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