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
[![License](https://img.shields.io/github/license/omaralalwi/laravel-time-craft.svg)](https://github.com/omaralalwi/laravel-time-craft/blob/main/LICENSE)

simple trait and helper functions that allow you, Effortlessly manage date and time queries in Laravel apps, with pre-built scopes and helper functions with ease.

## Features

- **Flexible Date Scopes**: Easily filter records based on various time frames (e.g., today, yesterday, current week, last month, etc.).
- **Helper Functions**: Utility functions for formatting dates, times, and human-readable date-time representations.
- **Dynamic Field Support**: Scopes can be applied to any date or time field in your models.

## Installation

You can install the package via Composer:

```php
composer require omaralalwi/laravel-time-craft
```

publish the package's configuration file:

```php
php artisan vendor:publish --tag=laravel-time-craft
```

## Usage

### Using the `HasDateTimeScopes` Trait

Add the `HasDateTimeScopes` trait to your Eloquent model:

```php
use Omaralalwi\LaravelTimeCraft\Traits\HasDateTimeScopes;

class Order extends Model
{
    use HasDateTimeScopes;
}
```

#### Available Scopes in `HasDateTimeScopes` trait

You can apply various scopes in your model queries. Below are the descriptions, usage examples, and corresponding outputs for each scope:


- **`today`** : Filters records created today.

  ```php
  $todayOrders = Order::today()->get();
  ```

- **`yesterday`** : Filters records created yesterday.

  ```php
  $yesterdayOrders = Order::yesterday()->get();
  ```

- **`oneWeekAgo`** : Filters records created in the last seven days.

  ```php
  $lastSevenDaysOrders = Order::oneWeekAgo()->get();
  ```

- **`lastWeek`** : Filters records created in the last week.

  ```php
  $lastWeekOrders = Order::lastWeek()->get();
  ```

- **`currentWeek`** : Filters records created in the current week.

  ```php
  $currentWeekOrders = Order::currentWeek()->get();
  ```

- **`oneMonthAgo`** : Filters records created in the last 30 days.

  ```php
  $ordersLast30Days = Order::oneMonthAgo()->get();
  ```

- **`lastMonth`** : Filters records created last month.

  ```php
  $lastMonthOrders = Order::lastMonth()->get();
  ```

- **`currentMonth`** : Filters records created in the current month.

  ```php
  $thisMonthOrders = Order::currentMonth()->get();
  ```

- **`lastYear`** : Filters records created in the last year.

  ```php
  $lastYearOrders = Order::lastYear()->get();
  ```

- **`oneYearAgo`** : Filters records created exactly one year ago.

  ```php
  $oneYearAgoOrders = Order::oneYearAgo()->get();
  ```

- **`currentYear`** : Filters records created in the current year.

  ```php
  $thisYearOrders = Order::currentYear()->get();
  ```

- **`betweenDates`** : Filters records within a specific date range.

  ```php
  $ordersBetweenDates = Order::betweenDates('2024-01-01', '2024-01-31')->get();
  ```

---

###  Customize Scoping field

all scopes using `created_at` by default.
You can override by three ways:-

** in config file as default for all models**
```php
'default_field' => 'your_specific_field'
```

** customize it for every model** : by adding following line in model class:

```php
class Order extends Model
{
    use HasDateTimeScopes;

    protected $dateField = 'updated_at';
}
```

** pass field name directly when using the scopes:**
```php
$lastWeekOrders = Order::lastWeek('updated_at')->get();
```

---

### Format Date Time Using Helper Functions

You can use the provided helper functions in your application (in Blade files or in any class). Below are the descriptions, usage examples, and corresponding outputs for each helper function:

- **`formatDate`** : Formats a given date to "Y-m-d" format.
  ```php
  $formattedDate = formatDate($order->created_at); // 2024-08-25
  ```

- **`formatTime`** : Formats a given time to "h:i:s A" format.
  ```php
  $formattedTime = formatTime($order->created_at); // 10:38:12 PM
  ```

- **`getHumanDateTime`** : Formats the created_at datetime to "Y-m-d H:i:s A" format.
  ```php
  $humanDateTime = getHumanDateTime($order->created_at); // 2017-02-15 10:38:12 PM
  ```

- **`formatDateTime`** : Formats a given date and time to "Y-m-d H:i:s A" format.
  ```php
  $formattedDateTime = formatDateTime($order->created_at); // 2017-02-15 10:38:12 PM
  ```

- **`formatTimeAgo`** : Gets a human-readable "time ago" format for a given date.
  ```php
  $timeAgo = formatTimeAgo($order->created_at); // 2 days ago
  ```

- **`startOfDay`** : Gets the start of the day for a given date.
  ```php
  $startOfDay = startOfDay($order->created_at); // 2024-08-23 00:00:00
  ```

- **`endOfDay`** : Gets the end of the day for a given date.
  ```php
  $endOfDay = endOfDay($order->created_at); // 2024-08-23 23:59:59
  ```

- **`isWeekend`** : Checks if a given date is on the weekend.
  ```php
  $isWeekend = isWeekend($order->created_at); // true or false
  ```

- **`addDays`** : Adds a specified number of days to a given date.
  ```php
  $futureDate = addDays($order->created_at, 10); // 2024-09-02
  ```

- **`subtractDays`** : Subtracts a specified number of days from a given date.
  ```php
  $pastDate = subtractDays($order->created_at, 10); // 2024-08-13
  ```

---

Here's a reformatted version of the "Helpful Packages" section:

---

## Helpful Packages

You may be interested in our other packages:

- **[Gpdf](https://github.com/omaralalwi/Gpdf)** : PDF converter for PHP & Laravel Applications, with support for storing files on S3 and handling Arabic content.
- **[laravel-taxify](https://github.com/omaralalwi/laravel-taxify)** : Provides a set of helper functions to simplify tax (VAT) calculations within Laravel applications.
- **[laravel-deployer](https://github.com/omaralalwi/laravel-deployer)** : Streamlined deployment for Laravel and Node.js apps, featuring zero-downtime.
- **[laravel-trash-cleaner](https://github.com/omaralalwi/laravel-trash-cleaner)** : Cleans logs and debug files for debugging packages like Clockwork & Telescope.

---

## Tests

tests will coming soon.


## Contributing

Contributions are welcome! Please read our [contributing guidelines](CONTRIBUTING.md) before submitting a pull request.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
