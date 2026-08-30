---
title: نمونه‌های استفاده
description: مثال‌های عملی برای دستورهای Laravel Scaffolder
---

# نمونه‌های استفاده

<div dir="rtl" markdown="1">

این مثال‌ها بر اساس نسخه فعلی دستور `make:module` و config پکیج نوشته شده‌اند.

## ابتدا Model و Migration را بسازید

Laravel Scaffolder لایه‌های اطراف Model را تولید می‌کند؛ خود Eloquent Model یا Migration دیتابیس را ایجاد نمی‌کند.

```bash
php artisan make:model Product -m
```

بعد از تعریف Migration/Model، لایه‌های ماژول را تولید کنید.

## پشته کامل API با فیلدهای Inline

```bash
php artisan make:module Product \
  --api \
  --requests \
  --tests \
  --actions \
  --policy \
  --swagger \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean"
```

بسته به config، این اجرا می‌تواند Repository، Service، DTO، Request، Resource، Action، Policy، Controller، Provider، Test و مستندات OpenAPI را تولید کند.

## استفاده از Migration موجود

```bash
php artisan make:module Product --api --from-migration
```

یا مسیر/hint مایگریشن را صریحاً بدهید:

```bash
php artisan make:module Product \
  --api \
  --from-migration=database/migrations/2026_01_15_create_products_table.php
```

## تولید پشته کامل

```bash
php artisan make:module Product --all \
  --fields="name:string,price:decimal(10,2)"
```

میانبر `-a` همان `--all` است:

```bash
php artisan make:module Product -a \
  --fields="name:string,price:decimal(10,2)"
```

## تولید بدون DTO

وقتی DTO غیرفعال باشد، Controller/Service/Action می‌توانند با payload آرایه‌ای کار کنند:

```bash
php artisan make:module Product \
  --api --requests --actions \
  --no-dto \
  --fields="name:string,price:decimal(10,2)"
```

## حذف اجزای انتخابی

گزینه‌ای با نام `--only=` در نسخه فعلی وجود ندارد. برای حذف بخش‌ها از فلگ‌های منفی استفاده کنید:

```bash
php artisan make:module Product \
  --no-controller \
  --no-resource \
  --no-test \
  --no-provider \
  --no-actions \
  --no-policy \
  --no-swagger \
  --fields="name:string"
```

Repository و Service خروجی‌های هسته‌ای `make:module` هستند و در اجرای معمول تولید می‌شوند.

## قرار دادن Controller در زیردایرکتوری

```bash
php artisan make:module Product \
  --api \
  --controller=Admin \
  --fields="name:string"
```

Namespace ریشه و مسیرهای خروجی از `config/module-generator.php` کنترل می‌شوند؛ نامی مثل `Admin\\Dashboard` روش تغییر namespace پکیج نیست.

## بازنویسی عمدی فایل‌ها

فایل‌های موجود به‌صورت پیش‌فرض skip می‌شوند. فقط وقتی واقعاً قصد جایگزینی دارید `--force` بدهید:

```bash
php artisan make:module Product --api --force \
  --fields="name:string,price:decimal(10,2)"
```

## شخصی‌سازی Templateها

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Templateهای منتشرشده در این مسیر قرار می‌گیرند:

```text
resources/stubs/module-generator/
```

پس از ویرایش، دوباره `make:module` را اجرا کنید و اگر باید فایل‌های موجود جایگزین شوند از `--force` استفاده کنید.

## اجرای تست‌های تولیدشده

وقتی تولید Test فعال باشد:

```bash
php artisan test tests/Feature/ProductCrudTest.php
```

مسیر دقیق تست از `tests.feature` در `config/module-generator.php` قابل تنظیم است.

## ادامه

- [شروع سریع](./quickstart.md)
- [مرجع CLI](./reference.md)
- [پیکربندی](./configuration.md)
- [تولید Schema-Aware](./features/schema-aware-generation.md)

</div>
