# Laravel Time Craft

<p align="center">
  <a href="https://github.com/omaralalwi/laravel-time-craft" target="_blank">
    <img src="https://raw.githubusercontent.com/omaralalwi/laravel-time-craft/master/public/images/laravel-time-craft.jpg" alt="Laravel Time Craft">
  </a>
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/omaralalwi/laravel-time-craft.svg?style=flat-square)](https://packagist.org/packages/omaralalwi/laravel-time-craft)
[![Total Downloads](https://img.shields.io/packagist/dt/omaralalwi/laravel-time-craft.svg?style=flat-square)](https://packagist.org/packages/omaralalwi/laravel-time-craft)
[![GitHub Issues](https://img.shields.io/github/issues/omaralalwi/laravel-time-craft.svg)](https://github.com/omaralalwi/laravel-time-craft/issues)
[![GitHub Stars](https://img.shields.io/github/stars/omaralalwi/laravel-time-craft.svg)](https://github.com/omaralalwi/laravel-time-craft/stargazers)
[![License](https://img.shields.io/github/license/omaralalwi/laravel-time-craft.svg)](https://github.com/omaralalwi/laravel-time-craft/blob/master/LICENSE.md)

A simple trait and a set of helper functions that let you effortlessly manage date and time queries in Laravel apps — with pre-built Eloquent scopes and formatting helpers, ready to use out of the box.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [The `HasDateTimeScopes` Trait](#the-hasdatetimescopes-trait)
  - [Available Scopes](#available-scopes)
  - [Customizing the Date Field](#customizing-the-date-field)
  - [A Note on Timezones](#a-note-on-timezones)
  - [The `HasReadableDates` Trait](#the-hasreadabledates-trait)
- [Helper Functions](#helper-functions)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [License](#license)
- [More Open Source Packages](#-more-open-source-packages)

## Features

- **Flexible date scopes** — filter records by common time frames (today, yesterday, current week, last month, last N days, and more).
- **Readable date accessors** — a drop-in trait that adds human-readable `readable_created_at` / `readable_updated_at` / `readable_deleted_at` accessors (and a generic `readableDate()` helper) to any model, without clobbering the real Carbon timestamps.
- **Helper functions** — utilities for formatting dates, times, and human-readable "time ago" representations.
- **Dynamic field support** — every scope can target any date/time column, configurable globally, per model, or per call.

## Requirements

- PHP `>= 7.4`
- Laravel (Eloquent / Carbon) — the scopes rely on Laravel's query builder and the `now()`/`today()` helpers.

## Installation

Install the package via Composer:

```bash
composer require omaralalwi/laravel-time-craft
```

The service provider and the `LaravelTimeCraft` facade are auto-discovered — no manual registration needed.

(Optional) publish the configuration file:

```bash
php artisan vendor:publish --tag=laravel-time-craft
```

## Configuration

Publishing creates `config/laravel-time-craft.php`:

```php
return [
    // The default column used by all scopes when no field is given.
    'default_field' => 'created_at',

    // The format used by the HasReadableDates trait for the readable_* accessors.
    'readable_datetime_format' => 'F j, Y g:i A',
];
```

When a scope needs to know which column to filter on, it resolves the field through a three-level fallback (first match wins):

1. The field passed **directly to the scope call**.
2. The model's `protected $dateField` property.
3. The `default_field` value from the config (`created_at` by default).

See [Customizing the Date Field](#customizing-the-date-field) for examples.

## Usage

### The `HasDateTimeScopes` Trait

Add the trait to any Eloquent model:

```php
use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LaravelTimeCraft\Traits\HasDateTimeScopes;

class Order extends Model
{
    use HasDateTimeScopes;
}
```

### Available Scopes

> **Heads up:** the `*Ago` scopes (`oneWeekAgo`, `oneMonthAgo`, `oneYearAgo`) match a **single, exact day** in the past — *not* a range. To filter everything *since* a point in time, use the `lastNDays` range scopes instead.

#### Day

| Scope | Description | Example |
|---|---|---|
| `today()` | Records dated today. | `Order::today()->get();` |
| `yesterday()` | Records dated yesterday. | `Order::yesterday()->get();` |

#### Exact day in the past (`*Ago`)

| Scope | Description | Example |
|---|---|---|
| `oneWeekAgo()` | Records dated **exactly** 7 days ago. | `Order::oneWeekAgo()->get();` |
| `oneMonthAgo()` | Records dated **exactly** 30 days ago. | `Order::oneMonthAgo()->get();` |
| `oneYearAgo()` | Records dated **exactly** one year ago. | `Order::oneYearAgo()->get();` |

#### Last N days (range, from N days ago until now)

| Scope | Description | Example |
|---|---|---|
| `last7Days()` | Records from the last 7 days. | `Order::last7Days()->get();` |
| `last10Days()` | Records from the last 10 days. | `Order::last10Days()->get();` |
| `last14Days()` | Records from the last 14 days. | `Order::last14Days()->get();` |
| `last15Days()` | Records from the last 15 days. | `Order::last15Days()->get();` |
| `last21Days()` | Records from the last 21 days. | `Order::last21Days()->get();` |
| `last30Days()` | Records from the last 30 days. | `Order::last30Days()->get();` |
| `lastDays($field = null, $days = 7)` | Records from the last `$days` days. | `Order::lastDays(null, 12)->get();` |

> **Argument order for `lastDays`:** the **field comes first**, the number of days second. Pass `null` for the field to use the default. Example: `Order::lastDays(null, 5)->get();` filters the last 5 days on the default field; `Order::lastDays('updated_at', 5)->get();` filters on `updated_at`.

#### Week

| Scope | Description | Example |
|---|---|---|
| `currentWeek()` | Records in the current calendar week. | `Order::currentWeek()->get();` |
| `lastWeek()` | Records in the previous calendar week. | `Order::lastWeek()->get();` |

#### Month

| Scope | Description | Example |
|---|---|---|
| `currentMonth()` | Records in the current month. | `Order::currentMonth()->get();` |
| `lastMonth()` | Records in the previous month. | `Order::lastMonth()->get();` |

#### Year

| Scope | Description | Example |
|---|---|---|
| `currentYear()` | Records in the current year. | `Order::currentYear()->get();` |
| `lastYear()` | Records in the previous year. | `Order::lastYear()->get();` |

#### Custom range

| Scope | Description | Example |
|---|---|---|
| `betweenDates($start, $end, $field = null)` | Records within an inclusive date range. Accepts `Carbon` instances or `Y-m-d` strings. | `Order::betweenDates('2024-01-01', '2024-01-31')->get();` |

### Customizing the Date Field

All scopes use `created_at` by default. You can override the field in three ways:

**1. Globally**, for every model, via the config file:

```php
'default_field' => 'your_specific_field',
```

**2. Per model**, by adding a `$dateField` property:

```php
class Order extends Model
{
    use HasDateTimeScopes;

    protected $dateField = 'updated_at';
}
```

**3. Per call**, by passing the field name directly:

```php
$lastWeekOrders = Order::lastWeek('updated_at')->get();
```

### A Note on Timezones

Scopes are built on Laravel's `now()` / `today()` helpers, so "today", "this week", etc. are evaluated against your application timezone (`config('app.timezone')`). Make sure your app timezone and the stored timestamps are consistent to get the results you expect.

### The `HasReadableDates` Trait

Tired of formatting `created_at` / `updated_at` / `deleted_at` in every model, controller, and Blade view? Add the trait once and get ready-to-display, human-readable date accessors:

```php
use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LaravelTimeCraft\Traits\HasReadableDates;

class Order extends Model
{
    use HasReadableDates;
}
```

```php
$order->readable_created_at;   // "April 30, 2023 3:49 PM"
$order->readable_updated_at;   // "April 30, 2023 3:49 PM"
$order->readable_deleted_at;   // "April 30, 2023 3:49 PM" (only when the model uses SoftDeletes)
```

> **Non-destructive by design:** unlike overriding the timestamp attributes directly, this trait adds **separate** `readable_*` accessors. Your real `$order->created_at` keeps returning a `Carbon` instance, so date math, sorting, comparisons and casting all keep working — you just get a formatted string on the side.

**Format any date field.** Use `readableDate()` for columns beyond the standard timestamps:

```php
$order->readableDate('shipped_at');   // "April 30, 2023 3:49 PM"
```

**Include them in JSON / API responses** by appending the accessors:

```php
class Order extends Model
{
    use HasReadableDates;

    protected $appends = ['readable_created_at'];
}
```

**Safe on models without the columns.** `readable_deleted_at` returns `null` unless the model uses `SoftDeletes` (detected recursively, so it also works when `SoftDeletes` comes from a parent class), and the `readable_created_at` / `readable_updated_at` accessors return `null` when the model has `public $timestamps = false`.

#### Customizing the format

The format is resolved through a three-level fallback (first match wins):

1. The model's `protected $readableDateFormat` property.
2. The `readable_datetime_format` value from the config.
3. The built-in default, `F j, Y g:i A`.

```php
// Per model:
class Order extends Model
{
    use HasReadableDates;

    protected $readableDateFormat = 'd/m/Y H:i';
}
```

```php
// Globally, in config/laravel-time-craft.php:
'readable_datetime_format' => 'd M Y, g:i A',
```

## Helper Functions

These globally-available functions can be used anywhere (Blade files, classes, controllers). Each accepts a `\DateTime`/`Carbon` instance or a date string.

| Function | Description | Example | Output |
|---|---|---|---|
| `formatDate($date)` | Format a date as `Y-m-d`. | `formatDate($order->created_at)` | `2024-08-25` |
| `formatTime($time)` | Format a time as `h:i:s A`. | `formatTime($order->created_at)` | `10:38:12 PM` |
| `formatDateTime($dateTime)` | Format date + time as `Y-m-d H:i:s A`. Parses strings too. | `formatDateTime($order->created_at)` | `2017-02-15 10:38:12 PM` |
| `getHumanDateTime($createdAt)` | Format a `Carbon` instance as `Y-m-d H:i:s A`. Returns the input unchanged if it is **not** a `Carbon` instance. | `getHumanDateTime($order->created_at)` | `2017-02-15 10:38:12 PM` |
| `formatTimeAgo($dateTime)` | Human-readable "time ago". | `formatTimeAgo($order->created_at)` | `2 days ago` |
| `startOfDay($date)` | Start of the given day. | `startOfDay($order->created_at)` | `2024-08-23 00:00:00` |
| `endOfDay($date)` | End of the given day. | `endOfDay($order->created_at)` | `2024-08-23 23:59:59` |
| `isWeekend($date)` | Whether the date falls on Sat/Sun. | `isWeekend($order->created_at)` | `true` / `false` |
| `addDays($date, $days)` | Add days, returns `Y-m-d`. | `addDays($order->created_at, 10)` | `2024-09-02` |
| `subtractDays($date, $days)` | Subtract days, returns `Y-m-d`. | `subtractDays($order->created_at, 10)` | `2024-08-13` |

> `getHumanDateTime` and `formatDateTime` produce the same format, but differ in input handling: `getHumanDateTime` only formats `Carbon` instances (anything else is returned as-is), while `formatDateTime` also parses date strings.

## Testing

```bash
composer install   # install dev dependencies first
composer test      # run the PHPUnit suite

composer test-coverage   # run with an HTML coverage report in ./coverage
```

The package uses [Orchestra Testbench](https://github.com/orchestral/testbench) to boot a minimal Laravel app for testing.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for details on what has changed recently.

## Contributing

Contributions are welcome! Please read our [contributing guidelines](CONTRIBUTING.md) before submitting a pull request.

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

---

## 📚 More Open Source Packages

- <a href="https://github.com/omaralalwi/lexi-translate"><img src="https://raw.githubusercontent.com/omaralalwi/lexi-translate/master/public/images/lexi-translate-banner.jpg" width="26" height="26" style="border-radius:13px;" alt="Lexi Translate" /> **Lexi Translate** </a> simplify managing translations for multilingual Eloquent models with the power of morph relationships and caching.

- <a href="https://github.com/omaralalwi/Gpdf"><img src="https://raw.githubusercontent.com/omaralalwi/Gpdf/master/public/images/gpdf-banner-bg.jpg" width="26" height="26" style="border-radius:13px;" alt="Gpdf" /> **Gpdf** </a> Open Source HTML to PDF converter for PHP & Laravel applications, supports Arabic content out-of-the-box and other languages.

- <a href="https://github.com/omaralalwi/laravel-taxify"><img src="https://raw.githubusercontent.com/omaralalwi/laravel-taxify/master/public/images/taxify.jpg" width="26" height="26" style="border-radius:13px;" alt="Laravel Taxify" /> **Laravel Taxify** </a> a set of helper functions and classes to simplify tax (VAT) calculations within Laravel applications.

- <a href="https://github.com/omaralalwi/laravel-deployer"><img src="https://raw.githubusercontent.com/omaralalwi/laravel-deployer/master/public/images/deployer.jpg" width="26" height="26" style="border-radius:13px;" alt="Laravel Deployer" /> **Laravel Deployer** </a> Streamlined deployment for Laravel and Node.js apps, with zero-downtime across various environments and branches.

- <a href="https://github.com/omaralalwi/laravel-trash-cleaner"><img src="https://raw.githubusercontent.com/omaralalwi/laravel-trash-cleaner/master/public/images/laravel-trash-cleaner.jpg" width="26" height="26" style="border-radius:13px;" alt="Laravel Trash Cleaner" /> **Laravel Trash Cleaner** </a> clean logs and debug files for debugging packages.

- <a href="https://github.com/omaralalwi/laravel-startkit"><img src="https://raw.githubusercontent.com/omaralalwi/laravel-startkit/master/public/screenshots/backend-rtl.png" width="26" height="26" style="border-radius:13px;" alt="Laravel Startkit" /> **Laravel Startkit** </a> Laravel Admin Dashboard & Admin Template with a frontend template, for scalable Laravel projects.
