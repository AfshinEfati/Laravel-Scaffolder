# Jalali Date Support

Laravel Scaffolder includes built-in Jalali date utilities through the `Goli` class, the `goli()` / `goli_date()` helpers, an Eloquent cast, and a model trait.

## Create a `Goli` Instance

```php
$goli = goli(now());
$goli = goli('2024-05-01 12:00:00');
```

The helper accepts Carbon/DateTime values, timestamps, date strings, arrays, existing `Goli` instances, and an optional timezone.

## Parse a Jalali Date

```php
use Efati\ModuleGenerator\Support\Goli;

$goli = Goli::parseGoli('1403-02-12 12:00:00');
```

## Format Jalali Dates

```php
$goli->toGoliDateString();
$goli->toGoliDateTimeString();
$goli->format('Y/m/d H:i:s');
```

Use Persian digits when needed:

```php
$goli->format('Y/m/d', true);
$goli->toGoliDateString(true);
```

## Convert Back to Carbon

```php
$carbon = $goli->toCarbon();
```

## Human-Readable Differences

```php
$goli->diffForHumans();
$goli->diffForHumans(null, true); // Persian digits
```

## Eloquent Cast

```php
use Efati\ModuleGenerator\Casts\GoliDateCast;

protected function casts(): array
{
    return [
        'scheduled_at' => GoliDateCast::class,
    ];
}
```

The value is stored in Gregorian form and returned as a `Goli` instance.

## Model Trait

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

You can also register a cast dynamically:

```php
$event->addGoliDateCast('published_at');
```

## API Response Helper

The publishable `ApiResponseHelper` exposes an explicit `formatDates()` method:

```php
$dates = ApiResponseHelper::formatDates(now());
```

It returns Gregorian date/time, a Persian-digit Jalali date, and ISO-8601 output. The helper does **not** globally or automatically convert every Carbon value in API responses.

## Carbon Macros

The current package does not register `Carbon::toJalali()` or `Carbon::fromJalali()` macros. Use `goli()`, `Goli::parseGoli()`, and `toCarbon()` instead.

See the [Public PHP API](../api-reference.md) for more examples.
