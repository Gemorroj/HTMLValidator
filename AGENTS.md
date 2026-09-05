# AGENTS.md

This is a small PHP library (`gemorroj/htmlvalidator`) — a client for the W3C HTML Validator API.

## Stack

- PHP >= 8.2, strict types, PSR-4 (`HTMLValidator\` -> `src/`)
- Runtime dependency: Symfony HttpClient
- Dev tools: PHPUnit, PHP-CS-Fixer

## Project layout

- `src/` — library code (`HTMLValidator.php`, `Response.php`, `Message.php`, `Error.php`, `Warning.php`, `Options.php`, `Exception.php`)
- `tests/` — PHPUnit tests
- `composer.json`, `phpunit.xml.dist`, `.php-cs-fixer.dist.php`

## Commands

```bash
composer install
vendor/bin/phpunit
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

## Conventions

- Keep changes minimal and focused; do not add new dependencies without need.
- Follow existing code style (PSR-12 + PHP-CS-Fixer config); use `declare(strict_types=1);`.
- Keep public API backward compatible; update `README.md` examples if behavior changes.
- Add or update PHPUnit tests for bug fixes and new features.
- Do not commit `vendor/`, `.phpunit.result.cache`, or `.php-cs-fixer.cache`.
