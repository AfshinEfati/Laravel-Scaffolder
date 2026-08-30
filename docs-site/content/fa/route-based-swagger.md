---
title: OpenAPI مبتنی بر Route و Swagger UI
description: تولید OpenAPI JSON از Routeهای لاراول و اجرای Swagger UI داخلی پکیج
---

# OpenAPI مبتنی بر Route و Swagger UI

<div dir="rtl" markdown="1">

Laravel Scaffolder علاوه بر گزینه `--swagger` هنگام تولید ماژول، یک workflow مستقل و بدون وابستگی خارجی برای ساخت OpenAPI JSON از Routeهای اپلیکیشن دارد.

## دو مسیر Swagger

### مستندات هنگام تولید ماژول

اگر هنگام ساخت ماژول خروجی Swagger/OpenAPI مربوط به همان ماژول را می‌خواهید از `--swagger` استفاده کنید:

```bash
php artisan make:module Product --api --swagger \
  --fields="name:string,price:decimal(10,2)"
```

### تولید JSON از Routeهای اپلیکیشن

دستور `swagger:generate` Routeهای API ثبت‌شده را بررسی می‌کند و یک specification با فرمت OpenAPI 3 JSON می‌سازد:

```bash
php artisan swagger:generate
```

برای این مسیر نیازی به L5-Swagger یا swagger-php نیست.

## workflow پیشنهادی

ابتدا فایل‌های UI داخلی را یک‌بار آماده کنید:

```bash
php artisan swagger:init
```

سپس specification را تولید کنید:

```bash
php artisan swagger:generate
```

و Swagger UI مستقل را اجرا کنید:

```bash
php artisan swagger:ui
```

آدرس پیش‌فرض:

```text
http://localhost:8000
```

برای تولید دوباره JSON درست قبل از اجرای UI:

```bash
php artisan swagger:ui --refresh
```

## `swagger:init`

```text
swagger:init
  --force    بازنویسی فایل‌های UI موجود
```

این دستور فایل‌های Swagger UI را در `storage/swagger-ui` آماده می‌کند. اگر `swagger.json` تولیدشده از قبل وجود داشته باشد، هنگام refresh شدن assetهای UI حفظ می‌شود.

## `swagger:generate`

```text
swagger:generate
  --output=     مسیر دلخواه swagger.json
  --title=      عنوان API (پیش‌فرض: API Documentation)
  --version=    نسخه API (پیش‌فرض: 1.0.0)
  --host=       جایگزینی Server URL
```

مثال:

```bash
php artisan swagger:generate \
  --title="Store API" \
  --version="2.0.0" \
  --host="https://api.example.com"
```

مسیر خروجی سفارشی:

```bash
php artisan swagger:generate --output=storage/api/openapi.json
```

اگر `--output` ندهید، مسیر از تنظیمات Swagger در config پکیج خوانده می‌شود.

## `swagger:ui`

```text
swagger:ui
  --port=8000       پورت اجرا
  --host=localhost  Host/IP برای bind
  --refresh         اجرای swagger:generate قبل از شروع UI
```

مثال:

```bash
php artisan swagger:ui --port=8080
php artisan swagger:ui --host=127.0.0.1 --port=8080 --refresh
```

برای جلوگیری از command injection، مقدار host فقط می‌تواند `localhost` یا یک IP معتبر باشد.

## `swagger:config`

نمایش تنظیمات فعلی:

```bash
php artisan swagger:config --show
```

خروجی تنظیمات قابل استفاده در `.env`:

```bash
php artisan swagger:config --export-env
```

گزینه‌های دستور:

```text
--show
--export-env
--theme=vanilla|tailwind|dark
--primary-color=
--secondary-color=
--title=
--reset
```

اجرای `swagger:config` بدون گزینه، حالت تعاملی را باز می‌کند.

## پیکربندی

تنظیمات Swagger زیر کلید `swagger` در `config/module-generator.php` قرار دارند و این موارد را کنترل می‌کنند:

- Theme، رنگ‌ها و فونت‌های UI
- Dark Mode
- عنوان و تنظیمات نمایش
- host/port سرور مستقل
- مسیر و نام فایل specification
- middleware احراز هویت و Security Schemeهای OpenAPI

چند Environment Variable پرکاربرد:

```dotenv
SWAGGER_THEME=vanilla
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_AUTH_MIDDLEWARE=auth,auth:api,auth:sanctum
```

ساختار کامل در [راهنمای پیکربندی](./configuration.md) آمده است.

## دستور قدیمی

`make:swagger` برای backward compatibility با مسیر قدیمی تولید Swagger همچنان ثبت شده است. برای workflow مبتنی بر Route و JSON که در این صفحه توضیح داده شد از `swagger:generate` استفاده کنید.

## دستورهایی که وجود ندارند

Laravel Scaffolder دستورهای `generate:swagger`، `swagger:export` یا `swagger:docs` را ثبت نمی‌کند. برای تولید فایل JSON از `swagger:generate` و گزینه `--output` استفاده کنید.

</div>
