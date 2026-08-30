---
title: API عمومی PHP
description: Helperها و یکپارچه‌سازی تاریخ جلالی در Laravel Scaffolder
---

# API عمومی PHP

<div dir="rtl" markdown="1">

Laravel Scaffolder در درجه اول یک Code Generator مبتنی بر Artisan است. API عمومی runtime آن بیشتر روی پشتیبانی داخلی تاریخ جلالی متمرکز است.

برای گزینه‌های Generator به [مرجع CLI](./reference.md) مراجعه کنید.

## `goli()`

Helper سراسری `goli()` از ورودی‌های تاریخ پشتیبانی‌شده یک نمونه `Goli` می‌سازد:

```php
$jalali = goli(now());
$jalali = goli('2024-03-20 12:00:00');
$jalali = goli(1710936000);
```

Timezone اختیاری را می‌توان به‌عنوان آرگومان دوم داد:

```php
$jalali = goli('2024-03-20 12:00:00', 'Asia/Tehran');
```

`goli_date()` alias همین helper با همان ورودی و خروجی است.

## `Goli`

```php
use Efati\ModuleGenerator\Support\Goli;
```

ساخت و Parse کردن:

```php
$now = Goli::now();
$date = Goli::parse('2024-03-20 12:00:00');
$jalali = Goli::parseGoli('1403-01-01 12:00:00');
$created = Goli::create(1403, 1, 1, 12, 0, 0);
$fromTimestamp = Goli::fromTimestamp(time());
```

چند متد پرکاربرد:

```php
$jalali->format('Y/m/d H:i:s');
$jalali->toGoliDateString();
$jalali->toGoliDateTimeString();
$jalali->toGoliArray(withTime: true);
$jalali->toCarbon();
$jalali->toIso8601String();
$jalali->diffForHumans();
```

برای خروجی با ارقام فارسی:

```php
$jalali->toGoliDateString(true);
$jalali->format('Y/m/d', true);
$jalali->diffForHumans(null, true);
```

## `GoliDateCast`

برای attributeهای Eloquent که باید هنگام خواندن به `Goli` تبدیل شوند ولی در دیتابیس با تاریخ Gregorian ذخیره شوند:

```php
use Efati\ModuleGenerator\Casts\GoliDateCast;

protected function casts(): array
{
    return [
        'scheduled_at' => GoliDateCast::class,
    ];
}
```

Cast در زمان set می‌تواند تاریخ Gregorian، Carbon/DateTime، رشته/آرایه جلالی و نمونه `Goli` را دریافت کند.

## `HasGoliDates`

Trait می‌تواند چند attribute را از روی property مدل به `GoliDateCast` متصل کند:

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

در runtime هم می‌توان attribute جدید اضافه کرد:

```php
$event->addGoliDateCast('published_at');
```

## `ApiResponseHelper::formatDates()`

`ApiResponseHelper` قابل انتشار، یک متد صریح برای فرمت تاریخ دارد:

```php
$result = ApiResponseHelper::formatDates(now());
```

خروجی شامل تاریخ/زمان Gregorian، تاریخ جلالی با ارقام فارسی و ISO-8601 است. تبدیل تاریخ در responseها خودکار و global نیست و باید این متد را صریحاً استفاده کنید.

## Container Binding

Service Provider کلید `goli` را bind و به کلاس `Goli` alias می‌کند:

```php
$jalali = app('goli', [
    'datetime' => now(),
    'timezone' => 'Asia/Tehran',
]);
```

## مثال اجرایی

مثال مستقل تبدیل تاریخ در این فایل قرار دارد:

```text
examples/goli-date.php
```

بعد از نصب dependencyها:

```bash
php examples/goli-date.php
```

## Facade یا Service با نام ModuleGenerator وجود ندارد

نسخه فعلی پکیج Facade با namespace قدیمی `AfshinEfati\LaravelModuleGenerator\Facades\ModuleGenerator` یا Service با نام `ModuleGenerator` ارائه نمی‌کند. تولید ماژول از طریق Artisan commandهای ثبت‌شده انجام می‌شود.

</div>
