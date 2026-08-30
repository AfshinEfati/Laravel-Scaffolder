# پشتیبانی از تاریخ جلالی

<div dir="rtl" markdown="1">

Laravel Scaffolder ابزارهای داخلی تاریخ جلالی را از طریق کلاس `Goli`، helperهای `goli()` و `goli_date()`، Cast مربوط به Eloquent و Trait مدل ارائه می‌کند.

## ساخت نمونه `Goli`

```php
$goli = goli(now());
$goli = goli('2024-05-01 12:00:00');
```

Helper می‌تواند Carbon/DateTime، timestamp، رشته تاریخ، آرایه، نمونه موجود `Goli` و timezone اختیاری را دریافت کند.

## Parse کردن تاریخ جلالی

```php
use Efati\ModuleGenerator\Support\Goli;

$goli = Goli::parseGoli('1403-02-12 12:00:00');
```

## فرمت تاریخ جلالی

```php
$goli->toGoliDateString();
$goli->toGoliDateTimeString();
$goli->format('Y/m/d H:i:s');
```

برای ارقام فارسی:

```php
$goli->format('Y/m/d', true);
$goli->toGoliDateString(true);
```

## تبدیل به Carbon

```php
$carbon = $goli->toCarbon();
```

## اختلاف زمانی خوانا

```php
$goli->diffForHumans();
$goli->diffForHumans(null, true); // با ارقام فارسی
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

مقدار در قالب Gregorian ذخیره و هنگام خواندن به نمونه `Goli` تبدیل می‌شود.

## Trait مدل

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

در runtime نیز می‌توانید Cast جدید اضافه کنید:

```php
$event->addGoliDateCast('published_at');
```

## ApiResponseHelper

`ApiResponseHelper` قابل انتشار متد صریح `formatDates()` را ارائه می‌کند:

```php
$dates = ApiResponseHelper::formatDates(now());
```

خروجی شامل تاریخ/زمان Gregorian، تاریخ جلالی با ارقام فارسی و ISO-8601 است. این Helper همه Carbonهای response را به‌صورت global و خودکار تبدیل نمی‌کند.

## Carbon Macro

نسخه فعلی پکیج macroهای `Carbon::toJalali()` یا `Carbon::fromJalali()` را ثبت نمی‌کند. به‌جای آن از `goli()`، `Goli::parseGoli()` و `toCarbon()` استفاده کنید.

برای مثال‌های بیشتر [API عمومی PHP](../api-reference.md) را ببینید.

</div>
