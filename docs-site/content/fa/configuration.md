# پیکربندی

<div dir="rtl" markdown="1">

[🇬🇧 English](../en/configuration.md){ .language-switcher }

Laravel Scaffolder را یک‌بار تنظیم کنید تا همهٔ ماژول‌های تولیدشده در پروژه ساختار یکسانی داشته باشند.

## انتشار پیکربندی

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

این دستور فایل `config/module-generator.php` و کلاس‌های پایه/Helper پیش‌فرض پکیج را منتشر می‌کند.

## نام‌فضای پایه

نام‌فضای ریشه با کلید `base_namespace` تنظیم می‌شود:

```php
'base_namespace' => 'App',
```

فقط زمانی مقدار دیگری قرار دهید که Autoload پروژه نیز برای همان PSR-4 root تنظیم شده باشد.

## مسیر فایل‌های تولیدشده

مقادیر `paths` نسبت به پوشه `app/` هستند:

```php
'paths' => [
    'repository' => [
        'eloquent' => 'Repositories/Eloquent',
        'contracts' => 'Repositories/Contracts',
    ],
    'service' => [
        'concretes' => 'Services',
        'contracts' => 'Services/Contracts',
    ],
    'dto' => 'DTOs',
    'provider' => 'Providers',
    'controller' => [
        'api' => 'Http/Controllers/Api/V1',
        'web' => 'Http/Controllers',
    ],
    'resource' => 'Http/Resources',
    'form_request' => 'Http/Requests',
    'actions' => 'Actions',
    'docs' => 'Docs',
],
```

مسیر تست‌های Feature نسبت به ریشه پروژه تعریف می‌شود:

```php
'tests' => [
    'feature' => 'tests/Feature',
],
```

## پیش‌فرض‌های دستور

پیکربندی فعلی پکیج این پیش‌فرض‌ها را دارد:

```php
'defaults' => [
    'with_controller' => true,
    'with_form_requests' => false,
    'with_unit_test' => true,
    'with_resource' => true,
    'with_dto' => true,
    'with_provider' => true,
    'with_actions' => false,
    'controller_middleware' => [],
    'controller_type' => 'api', // 'api' یا 'web'
],
```

فلگ‌های CLI برای هر اجرای جداگانه این مقادیر را override می‌کنند. دستور همچنین در صورت اضافه‌کردن `with_policy` و `with_swagger` به config منتشرشده، آن‌ها را به‌عنوان پیش‌فرض می‌خواند.

## Swagger UI و OpenAPI

تنظیمات Swagger زیر کلید `swagger` قرار دارند و شامل این بخش‌ها هستند:

- `theme` – یکی از `vanilla`، `tailwind` یا `dark`
- `colors` – رنگ‌های رابط کاربری
- `fonts` – فونت معمولی و monospace
- `dark_mode` – فعال‌بودن، حالت پیش‌فرض و ذخیره تنظیمات
- `display` – عنوان، توضیح، نمایش Model/Example و نگهداری Auth
- `server` – host و port
- `spec` – مسیر خروجی، نام فایل و تنظیم دسترسی `swagger.json`
- `security` – middleware احراز هویت و Security Schemeهای OpenAPI

نمونه تنظیم Security:

```php
'security' => [
    'auth_middleware' => env(
        'SWAGGER_AUTH_MIDDLEWARE',
        'auth,auth:api,auth:sanctum'
    ),
    'default' => 'bearerAuth',
    'secure_spec' => env('SWAGGER_SECURE_SPEC', false),
    'schemes' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearer_format' => 'JWT',
        ],
    ],
],
```

## متغیرهای محیطی

config فعلی برای تنظیمات Swagger و کانال لاگ پکیج از Environment Variable استفاده می‌کند. چند نمونه:

```dotenv
SWAGGER_THEME=vanilla
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_AUTH_MIDDLEWARE=auth,auth:api,auth:sanctum
MODULE_GENERATOR_LOG_CHANNEL=
```

برای فهرست کامل، خود فایل `config/module-generator.php` منتشرشده منبع نهایی است.

## ثبت Provider تولیدشده

وقتی تولید Provider فعال باشد، Laravel Scaffolder فایل Service Provider ماژول را ایجاد می‌کند و تلاش می‌کند آن را در اپلیکیشن ثبت کند:

- اگر `bootstrap/providers.php` وجود داشته باشد، Provider به آن اضافه می‌شود.
- در غیر این صورت، اگر `config/app.php` آرایه `providers` داشته باشد، Provider به همان آرایه اضافه می‌شود.

در نتیجه رفتار به یک نسخه خاص از Laravel وابسته و hard-code نشده است.

## انتشار Stubهای سفارشی

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

قالب‌ها در `resources/stubs/module-generator/` قرار می‌گیرند. برای هماهنگ‌کردن خروجی ژنراتور با استانداردهای پروژه، همین فایل‌ها را ویرایش کنید.

</div>
