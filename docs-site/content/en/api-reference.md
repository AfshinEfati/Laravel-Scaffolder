---
title: Public PHP API
description: Public helpers and Jalali date integration provided by Laravel Scaffolder
---

# Public PHP API

Laravel Scaffolder is primarily an Artisan-driven code generator. Its public runtime API is focused on the built-in Jalali date support.

For generator command options, see the [CLI reference](./reference.md).

## `goli()`

The global `goli()` helper creates a `Goli` instance from supported date inputs:

```php
$jalali = goli(now());
$jalali = goli('2024-03-20 12:00:00');
$jalali = goli(1710936000);
```

An optional timezone can be passed as the second argument:

```php
$jalali = goli('2024-03-20 12:00:00', 'Asia/Tehran');
```

`goli_date()` is an alias with the same arguments and return type.

## `Goli`

```php
use Efati\ModuleGenerator\Support\Goli;
```

Common constructors/parsers:

```php
$now = Goli::now();
$date = Goli::parse('2024-03-20 12:00:00');
$jalali = Goli::parseGoli('1403-01-01 12:00:00');
$created = Goli::create(1403, 1, 1, 12, 0, 0);
$fromTimestamp = Goli::fromTimestamp(time());
```

Common output/conversion methods:

```php
$jalali->format('Y/m/d H:i:s');
$jalali->toGoliDateString();
$jalali->toGoliDateTimeString();
$jalali->toGoliArray(withTime: true);
$jalali->toCarbon();
$jalali->toIso8601String();
$jalali->diffForHumans();
```

Persian-digit output is supported by formatting helpers:

```php
$jalali->toGoliDateString(true);
$jalali->format('Y/m/d', true);
$jalali->diffForHumans(null, true);
```

## `GoliDateCast`

Use the package cast when an Eloquent attribute should be returned as a `Goli` instance while remaining Gregorian in storage:

```php
use Efati\ModuleGenerator\Casts\GoliDateCast;

protected function casts(): array
{
    return [
        'scheduled_at' => GoliDateCast::class,
    ];
}
```

The cast accepts Gregorian date values, Carbon/DateTime values, Jalali strings/arrays, and `Goli` instances when setting an attribute.

## `HasGoliDates`

The trait can register multiple `GoliDateCast` attributes from a model property:

```php
use Efati\ModuleGenerator\Support\HasGoliDates;

class Event extends Model
{
    use HasGoliDates;

    protected array $goliDates = [
        'starts_at',
        'ends_at',
    ];
}
```

Additional attributes can be registered at runtime:

```php
$event->addGoliDateCast('published_at');
```

## `ApiResponseHelper::formatDates()`

The publishable `ApiResponseHelper` includes an explicit date-formatting helper:

```php
$result = ApiResponseHelper::formatDates(now());
```

It returns a normalized array containing Gregorian date/time, Persian-digit Jalali date, and ISO-8601 output. Date conversion is explicit; API responses are not globally transformed automatically.

## Container Binding

The service provider binds `goli` and aliases it to the `Goli` class:

```php
$jalali = app('goli', [
    'datetime' => now(),
    'timezone' => 'Asia/Tehran',
]);
```

## Working Example

A standalone conversion example is available in:

```text
examples/goli-date.php
```

Run it after installing dependencies:

```bash
php examples/goli-date.php
```

## No ModuleGenerator Facade

The current package does not expose an `AfshinEfati\LaravelModuleGenerator\Facades\ModuleGenerator` facade or a `ModuleGenerator` service class. Module generation is performed through the registered Artisan commands.
