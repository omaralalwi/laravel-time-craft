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

### 1. Using the `HasDateTimeScopes` Trait

Add the `HasDateTimeScopes` trait to your Eloquent model:

```php
use Omaralalwi\LaravelTimeCraft\Traits\HasDateTimeScopes;

class Order extends Model
{
    use HasDateTimeScopes;
}
```

To merge the "Available Scopes" table with usage examples, you can integrate the description and parameters directly into the examples for better readability and clarity. Here's a streamlined approach that combines the table and usage examples:

---

### 2. Available Scopes

You can apply various scopes in your model queries. Below is a table with the description and usage examples for each scope:

| Scope          | Description                                      | Usage Example                                                                      |
|--------------------|--------------------------------------------------|------------------------------------------------------------------------------------|
| `today`            | Filters records created today.                  | ``` $todayOrders = Order::today()->get(); ```                                      |
| `yesterday`        | Filters records created yesterday.              | ``` $yesterdayOrders = Order::yesterday()->get(); ```                              |
| `oneWeekAgo`       | Filters records created in the last seven days. | ``` $lastSevenDaysOrders = Order::oneWeekAgo()->get(); ```                         |
| `lastWeek`         | Filters records created in the last week.       | ``` $lastWeekOrders = Order::lastWeek()->get(); ```                                |
| `currentWeek`      | Filters records created in the current week.    | ``` $currentWeekOrders = Order::currentWeek()->get(); ```                          |
| `oneMonthAgo`      | Filters records created in the last 30 days.    | ``` $ordersLast30Days = Order::oneMonthAgo()->get(); ```                           |
| `lastMonth`        | Filters records created last month.             | ``` $lastMonthOrders = Order::lastMonth()->get(); ```                              |
| `currentMonth`     | Filters records created in the current month.   | ``` $thisMonthOrders = Order::currentMonth()->get(); ```                           |
| `lastYear`         | Filters records created in the last year.       | ``` $lastYearOrders = Order::lastYear()->get(); ```                                |
| `oneYearAgo`       | Filters records created exactly one year ago.   | ``` $oneYearAgoOrders = Order::oneYearAgo()->get(); ```                            |
| `currentYear`      | Filters records created in the current year.    | ``` $thisYearOrders = Order::currentYear()->get(); ```                             |
| `betweenDates`     | Filters records within a specific date range.   | ``` $ordersBetweenDates = Order::betweenDates('2024-01-01', '2024-01-31')->get(); ```   |

---


### 3. Customize Scoping field

all scopes using `created_at` by default.
You can override by three ways:-

**in config file as default for all models**
```php
'default_field' => 'your_specific_field'
```

**customize it for every model** : by adding following line in model class:

```php
class Order extends Model
{
    use HasDateTimeScopes;

    protected $dateField = 'updated_at';
}
```

**or pass field name directly when using the scopes:**
```php
$lastWeekOrders = Order::lastWeek('updated_at')->get();
```

---

### 4. Available Helper Functions

You can use the provided helper functions in your application (in blade files or in any class). Below is a table with the description, usage examples, and the corresponding output for each helper function:

| helper Function    | Description                                       | Usage Example                                                      | Output                 |
|--------------------|---------------------------------------------------|--------------------------------------------------------------------|-------------------------------|
| `formatDate`       | Formats a given date to "Y-m-d" format.          | ```$formattedDate = formatDate($order->created_at);```             | `2024-08-25`                   |
| `formatTime`       | Formats a given time to "h:i:s A" format.        | ```$formattedTime = formatTime($order->created_at);```             | `10:38:12 PM`                 |
| `getHumanDateTime` | Formats the created_at date time to "Y-m-d H:i:s A" format. | ```$humanDateTime = getHumanDateTime($order->created_at);```       | `2017-02-15 10:38:12 PM`      |
| `formatDateTime`   | Formats a given date and time to "Y-m-d H:i:s A" format. | ```$formattedDateTime = formatDateTime($order->created_at);```     | `2017-02-15 10:38:12 PM`      |
| `formatTimeAgo`    | Gets a human-readable "time ago" format for a given date. | ```$timeAgo = formatTimeAgo($order->created_at);```                | `2 days ago`                   |
| `startOfDay`       | Gets the start of the day for a given date.      | ```$startOfDay = startOfDay($order->created_at);```               | `2024-08-23 00:00:00`         |
| `endOfDay`         | Gets the end of the day for a given date.        | ```$endOfDay = endOfDay($order->created_at);```                   | `2024-08-23 23:59:59`         |
| `isWeekend`        | Checks if a given date is on the weekend.        | ```$isWeekend = isWeekend($order->created_at);```                 | `true` or `false`             |
| `addDays`          | Adds a specified number of days to a given date. | ```$futureDate = addDays($order->created_at, 10);```               | `2024-09-02`                   |
| `subtractDays`     | Subtracts a specified number of days from a given date. | ```$pastDate = subtractDays($order->created_at, 10);```            | `2024-08-13`                   |

---

## Helpful Packages
You may be interested in our other packages:

| Package                                                                     | Description                                                                                                                                           |                                              
|---------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------|
| **[Gpdf](https://github.com/omaralalwi/Gpdf)**                                  | PDF converter for PHP & Laravel Applications, Support store to s3 & supports Arabic content out-of-the-box and other languages .                      |
| **[laravel-taxify](https://github.com/omaralalwi/laravel-taxify)**              | Laravel Taxify provides a set of helper functions to simplify tax (VAT) calculations within Laravel applications, that support multiple tax profiles. |
| **[laravel-deployer](https://github.com/omaralalwi/laravel-deployer)**          | Streamlined Deployment for Laravel and Node.js apps, with Zero-Downtime and various environments and branches,                                        |
| **[laravel-trash-cleaner](https://github.com/omaralalwi/laravel-trash-cleaner)** | clean logs and debug files for debugging packages (clockwork, laravel telescope and more), and free up space                                          |

---

## Contributing

Contributions are welcome! Please read our [contributing guidelines](CONTRIBUTING.md) before submitting a pull request.

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

