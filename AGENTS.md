# AGENTS.md

Small PHP library (`gemorroj/htmlvalidator`) — a client for the W3C HTML Validator API.

## Stack

- PHP >= 8.2, PSR-4 (`HTMLValidator\` -> `src/`)
- Runtime dependency: Symfony HttpClient
- Dev tools: PHPUnit, PHP-CS-Fixer

## Commands

```bash
composer install
vendor/bin/phpunit
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --dry-run --diff
PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix
```

## Conventions

- Explore `src/` and `tests/` to learn the structure and style; follow the existing PHP-CS-Fixer config.
- Keep changes minimal and focused; do not add new dependencies without need.
- Keep public API backward compatible; update `README.md` examples if behavior changes.
- Add or update PHPUnit tests for bug fixes and new features.
- Do not commit `vendor/`, `.phpunit.result.cache`, or `.php-cs-fixer.cache`.
