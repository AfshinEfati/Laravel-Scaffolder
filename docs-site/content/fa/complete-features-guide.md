---
title: نقشه قابلیت‌ها
description: نمای کلی قابلیت‌های فعلی Laravel Scaffolder
---

# نقشه قابلیت‌ها

<div dir="rtl" markdown="1">

Laravel Scaffolder لایه‌های قابل تنظیم یک اپلیکیشن Laravel را پیرامون Model/Schema موجود تولید می‌کند. این صفحه فقط نقشه قابلیت‌های فعلی است و برای جزئیات هر بخش به راهنمای تخصصی همان قابلیت لینک می‌دهد تا اطلاعات تکراری و قدیمی نشوند.

## تولید ماژول

دستور `make:module` می‌تواند این بخش‌ها را تولید کند:

- Repository و Contract آن
- Service و Contract آن
- DTO
- Controller از نوع API یا Web
- Form Requestهای Store/Update
- API Resource
- لایه Actions
- CRUD Policy
- Service Provider ماژول و ثبت آن در اپلیکیشن
- اسکلت Feature Test
- مستندات Swagger/OpenAPI

برای فلگ‌ها و shortcutهای دقیق فعلی [مرجع CLI](./reference.md) را ببینید.

## منابع Schema

ژنراتور می‌تواند metadata را از این منابع بگیرد:

- فیلدهای Inline با `--fields=`
- Migration موجود با `--from-migration`
- اطلاعات runtime/model در صورت در دسترس بودن

این metadata برای Validation، Relation، DTO، Resource، Test و مستندات normalize و merge می‌شود.

جزئیات: [تولید Schema-Aware](./features/schema-aware-generation.md)

> Laravel Scaffolder خود Eloquent Model، Migration، Factory یا Seeder را تولید نمی‌کند. در صورت نیاز آن‌ها را جداگانه در پروژه بسازید.

## Repository و Service

Repository و Service خروجی‌های هسته‌ای اجرای معمول `make:module` هستند. کلاس‌های پایه‌ای که با پکیج publish می‌شوند رفتار مشترک را فراهم می‌کنند و در پروژه مصرف‌کننده قابل سفارشی‌سازی‌اند.

بیشتر بخوانید:

- [Criteria Pattern](./features/criteria-pattern.md)
- [پیکربندی](./configuration.md)

## DTO

تولید DTO در config پیش‌فرض فعال است و با `--no-dto` می‌توان آن را برای یک اجرا غیرفعال کرد. بدون DTO، خروجی‌های Service/Action/Controller می‌توانند با payload آرایه‌ای کار کنند.

جزئیات: [تولید DTO](./features/dto-generation.md)

## Form Request و API Resource

با `--requests`، Requestهای Store/Update تولید می‌شوند. API Resource نیز به‌صورت پیش‌فرض فعال است مگر اینکه `--no-resource` بدهید.

Schema metadata برای ساخت Validation و Serialization استفاده می‌شود.

## لایه Actions

با `--actions` لایه Actions تولید می‌شود. مجموعه فعلی شامل یک `BaseAction` مشترک و این Actionها برای هر ماژول است:

- List
- Show
- Create
- Update
- Delete
- ListWithRelations

جزئیات: [Action Layer](./features/action-layer.md)

## Policy

برای تولید CRUD Policy از `--policy` استفاده کنید. اگر پیش‌فرض config فعال باشد و نخواهید Policy ساخته شود، `--no-policy` در دسترس است.

جزئیات: [تولید Policy](./features/policy-generation.md)

## Feature Test

`--tests` تولید Feature Test را فعال می‌کند و `--no-test` آن را غیرفعال می‌کند. مسیر خروجی از `tests.feature` قابل تنظیم است.

جزئیات: [تولید Test](./features/test-generation.md)

## OpenAPI و Swagger UI

دو workflow مرتبط وجود دارد:

1. `make:module --swagger` برای مستندات مربوط به تولید ماژول.
2. `swagger:generate` / `swagger:init` / `swagger:ui` برای OpenAPI JSON مبتنی بر Route و UI مستقل داخلی.

بیشتر بخوانید:

- [تولید Swagger](./features/swagger-generation.md)
- [OpenAPI مبتنی بر Route و Swagger UI](./route-based-swagger.md)

## تاریخ جلالی

API runtime پکیج شامل این موارد است:

- Helperهای `goli()` و `goli_date()`
- کلاس `Goli`
- `GoliDateCast`
- `HasGoliDates`
- متد صریح `ApiResponseHelper::formatDates()`

پکیج macroهای Carbon با نام `toJalali()` یا `fromJalali()` ثبت نمی‌کند.

جزئیات در [پشتیبانی تاریخ جلالی](./features/jalali-support.md) و [API عمومی PHP](./api-reference.md).

## Stubهای سفارشی

برای تغییر Templateهای تولیدشده:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

خروجی در این مسیر قرار می‌گیرد:

```text
resources/stubs/module-generator/
```

## پیکربندی

برای publish کردن config و کلاس‌های پایه:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

`config/module-generator.php`، Namespace ریشه، مسیرها، پیش‌فرض‌های تولید، تنظیمات Swagger و کانال Log را کنترل می‌کند.

جزئیات: [راهنمای پیکربندی](./configuration.md)

## مثال‌های عملی

برای دستورهای قابل کپی:

- [شروع سریع](./quickstart.md)
- [نمونه‌های استفاده](./usage-examples.md)
- [مرجع CLI](./reference.md)

</div>
