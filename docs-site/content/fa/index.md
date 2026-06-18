---
title: معرفی
description: پکیج Laravel Scaffolder برای تولید لایه‌های کامل یک قابلیت در پروژه‌های لاراولی.
---

# معرفی Laravel Scaffolder

این پکیج یک ابزار قدرتمند برای تولید خودکار لایه‌های مختلف پروژه شامل Repository، Service، DTO، Action، Policy و غیره است.

## نیازمندی‌ها

- PHP نسخه `8.1` یا بالاتر
- Laravel نسخه `10` یا `11`
- Composer 2

## نصب

نصب پکیج:

```bash
composer require efati/laravel-scaffolder
```

پس از نصب، برای انتشار فایل‌های تنظیمات و کلاس‌های پایه:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```
