# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`omaralalwi/laravel-time-craft` is a small, dependency-light Laravel package (PHP >= 7.4) that adds reusable date/time **query scopes** and standalone **date-formatting helper functions** to Laravel apps. It is a library, not an application — there is no app to "run"; behavior is exercised through the test suite and consumed by host Laravel projects.

## Commands

```bash
composer install              # install dev deps (orchestra/testbench, phpunit) — required before testing
composer test                 # run full PHPUnit suite (vendor/bin/phpunit)
composer test-coverage        # run suite + HTML coverage report into ./coverage
vendor/bin/phpunit --filter <TestMethodOrClass>   # run a single test
```

Note: `vendor/` is not committed and may be absent — run `composer install` first. The suite lives in `tests/` (`tests/Unit` for helpers, `tests/Feature` for scopes) and uses `orchestra/testbench`, which boots a minimal Laravel app; `tests/TestCase.php` sets up an in-memory SQLite DB and an `orders` table, and `tests/Support/Order.php` is the test model. Scope tests freeze time with `Carbon::setTestNow()` for determinism — keep that when adding time-dependent tests.

Resolver note: `orchestra/testbench ^8` pins Laravel 10.x, whose latest releases carry security advisories that Composer's `audit.block-insecure` (default on) excludes — `composer install` may fail to resolve. For local testing, temporarily `composer config audit.block-insecure false`, install, then unset it. The real fix is bumping the testbench/Laravel constraint.

## Architecture

Three source files under `src/`, wired by a single service provider:

- **`src/Traits/HasDateTimeScopes.php`** — the core. A trait host models `use`, exposing Eloquent query scopes (`today`, `yesterday`, `lastWeek`, `currentMonth`, `last7Days`…`last30Days`, `lastDays($field, $days)`, `betweenDates`, `currentYear`, etc.). Every scope resolves its target column through `getDateField($dateField)`, which follows a 3-level fallback: explicit argument → model's `protected $dateField` property → `config('laravel-time-craft.default_field')` (defaults to `created_at`). When adding a scope, keep this `getDateField()` pattern so the per-call / per-model / global override chain stays consistent.

- **`src/helpers.php`** — globally-autoloaded standalone functions (`formatDate`, `formatTime`, `getHumanDateTime`, `formatDateTime`, `formatTimeAgo`, `startOfDay`, `endOfDay`, `isWeekend`, `addDays`, `subtractDays`). Loaded via composer's `autoload.files`. Each is wrapped in `if (! function_exists(...))` to avoid collisions in host apps — preserve this guard for any new helper. They accept either `\DateTime`/`Carbon` instances or strings and branch on the type.

- **`src/LaravelTimeCraftServiceProvider.php`** — merges `config/config.php` under the `laravel-time-craft` key and publishes it (tag `laravel-time-craft`) to the host app's `config/laravel-time-craft.php`. Auto-discovered via the `extra.laravel.providers` entry in `composer.json` (also registers the `LaravelTimeCraft` facade alias).

## Conventions

- Scopes are written as `scope<Name>` methods returning `Builder`; the public call is `Model::name()`. Note the argument order quirk for parameterized scopes: `lastDays($dateField = null, $days = 7)` takes the field **first**, days second (e.g. `Order::lastDays(null, 12)`).
- `version` is hard-coded in `composer.json` (currently `1.0.2`); bump it there when releasing, and update `CHANGELOG.md` and `README.md`.
- The README is the public API documentation and is kept exhaustive — when adding/changing a scope or helper, update the corresponding README section to match.
