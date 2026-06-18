---
title: نصب
---

# نصب

پکیج Laravel Scaffolder به PHP 8.1 و لاراول ۱۰ یا ۱۱ نیاز دارد.

## مرحله ۱: نصب پکیج

نصب از طریق کامپوزر:

```bash
composer require efati/laravel-scaffolder
```

## مرحله ۲: انتشار دارایی‌ها

برای استفاده از کلاس‌های پایه و فایل تنظیمات در پروژه خود:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

## مرحله ۳: انتشار استاب‌ها (اختیاری)

اگر نیاز به شخصی‌سازی قالب کدهای تولید شده دارید:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```
