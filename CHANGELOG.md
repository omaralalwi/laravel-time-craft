# Changelog

All notable changes to `laravel-time-craft` will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/).

## 2.0.0 - Unreleased

- **Compatibility:** officially support Laravel 10, 11, 12 and 13. Declared explicit `illuminate/support` and `illuminate/database` (`^10.0|^11.0|^12.0|^13.0`) dependencies and raised the PHP floor to `^8.1` (was `>=7.4`). The dev matrix now spans `orchestra/testbench` `^8|^9|^10|^11` and PHPUnit `^10.5|^11.0|^12.0`. **Breaking:** dropping PHP 7.4 / Laravel < 10 is why this is a new major.
- Added the `HasReadableDates` trait: non-destructive `readable_created_at` / `readable_updated_at` / `readable_deleted_at` accessors and a generic `readableDate($field)` method, with a configurable format (`readable_datetime_format` config, or per-model `$readableDateFormat`). Verified against Laravel 12.
- Added a PHPUnit/Testbench test suite covering all scopes and helper functions.
- Fixed `formatDateTime()` and `getHumanDateTime()` to use the 12-hour format (`h`) so the `AM/PM` marker is correct (e.g. `10:38:12 PM` instead of `22:38:12 PM`).
- Migrated `phpunit.xml` to the PHPUnit 10 schema and added a `.gitignore`.

## 1.0.2 - 2024-08-27

- Added `lastNDays` range scopes (`last7Days`, `last10Days`, `last14Days`, `last15Days`, `last21Days`, `last30Days`) and the configurable `lastDays($field, $days)` scope.

## 1.0.1 - 2024-08-27

- Fixed the `*Ago` scopes (`oneWeekAgo`, `oneMonthAgo`, `oneYearAgo`) to match an exact day in the past.
- Fixed publishing of the configuration file.

## 1.0.0 - 2024-08-24

- Initial release.
