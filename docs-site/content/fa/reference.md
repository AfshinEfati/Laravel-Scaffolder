# مرجع CLI

<div dir="rtl" markdown="1">

[🇬🇧 English](../en/reference.md){ .language-switcher }

مرجع سریع دستور `make:module` و گزینه‌های فعلی آن.

## دستور

```bash
php artisan make:module {name} [options]
```

نام ماژول به StudlyCase تبدیل می‌شود؛ برای مثال `Product`، `BlogPost` یا `OrderItem`.

## گزینه‌ها

| گزینه | میانبر | کاربرد |
| --- | --- | --- |
| `--controller=Subdir` | `-c` | تولید/قرار دادن Controller در زیردایرکتوری دلخواه. |
| `--api` | — | استفاده از Controller با ساختار API. |
| `--requests` | `-r` | تولید Form Requestهای Store/Update. |
| `--tests` | `-t` | فعال‌کردن تولید تست Feature. |
| `--no-controller` | `-nc` | عدم تولید Controller. |
| `--no-resource` | `-nr` | عدم تولید API Resource. |
| `--no-dto` | `-nd` | عدم تولید DTO. |
| `--no-test` | `-nt` | عدم تولید تست Feature. |
| `--no-provider` | `-np` | عدم تولید Service Provider ماژول. |
| `--actions` | — | تولید لایه Actions. |
| `--no-actions` | — | عدم تولید Actions. |
| `--policy` | — | تولید Policy برای عملیات CRUD. |
| `--no-policy` | — | عدم تولید Policy. |
| `--swagger` | `-sg` | تولید مستندات Swagger/OpenAPI. |
| `--no-swagger` | — | عدم تولید Swagger/OpenAPI. |
| `--all` | `-a` | تولید پشته کامل ماژول. |
| `--full` | `-f` | حالت کامل تولید ماژول. |
| `--from-migration=` | `-fm` | استخراج فیلدها/روابط از مسیر یا hint مایگریشن. |
| `--fields=` | — | تعریف Schema به‌صورت inline. |
| `--force` | — | بازنویسی فایل‌های تولیدشده موجود. |

> `-a` میانبر `--all` و `-f` میانبر `--full` است؛ این دو به‌ترتیب میانبر `--api` و `--force` نیستند.

## نمونه‌های متداول

### ماژول کامل API

```bash
php artisan make:module Product --all \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean"
```

### ماژول API با قابلیت‌های انتخابی

```bash
php artisan make:module Product \
  --api --requests --tests --actions --policy --swagger \
  --fields="name:string:unique,price:decimal(10,2)"
```

### تولید از مایگریشن موجود

```bash
php artisan make:module Product --api --from-migration
```

یا مسیر/hint را مشخص کنید:

```bash
php artisan make:module Product \
  --from-migration=database/migrations/2024_01_15_create_products_table.php
```

### تولید حداقلی

```bash
php artisan make:module Product \
  --no-controller --no-resource --no-test --no-provider
```

## ساختار فیلدهای Inline

فرمت کلی فیلدها به‌شکل `name:type:modifier` و جداشده با کاما است:

```bash
php artisan make:module Product --fields="name:string:unique,price:decimal(10,2):nullable,is_active:boolean"
```

چند نمونه رایج:

```text
name:string:unique
price:decimal(10,2):nullable
metadata:json:nullable
user_id:foreignId:constrained(users)
```

برای جزئیات Parser به [راهنمای Schema-Aware](./features/schema-aware-generation.md) مراجعه کنید.

## خروجی‌های قابل تولید

بسته به فلگ‌ها و config، ماژول می‌تواند شامل این موارد باشد:

- Repository و Contract آن
- Service و Contract آن
- DTO
- Controller
- Form Requestهای Store/Update
- API Resource
- لایه Actions
- Policy
- Service Provider ماژول
- تست Feature
- مستندات Swagger/OpenAPI

لایه Actions فعلی شامل `BaseAction` مشترک و Actionهای List، Show، Create، Update، Delete و ListWithRelations برای هر ماژول است.

## مسیر خروجی

مسیرها از `config/module-generator.php` خوانده می‌شوند. با تنظیمات پیش‌فرض، فایل‌ها در ساختاری مشابه زیر پخش می‌شوند:

```text
app/
├── Actions/
├── DTOs/
├── Docs/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Policies/
├── Providers/
├── Repositories/
│   ├── Contracts/
│   └── Eloquent/
└── Services/
    └── Contracts/

tests/
└── Feature/
```

ژنراتور شما را به ساختار ثابت `app/Modules/{Module}` محدود نمی‌کند و مسیرها قابل تنظیم هستند.

## ثبت Provider ماژول

اگر تولید Provider فعال باشد، Laravel Scaffolder فایل Provider را می‌سازد و تلاش می‌کند آن را ثبت کند:

1. در `bootstrap/providers.php`، اگر این فایل وجود داشته باشد.
2. در غیر این صورت داخل آرایه `providers` در `config/app.php`، اگر موجود باشد.

## پیکربندی پکیج

برای overwrite یا غیرفعال‌کردن تست‌ها روی Environment Variableهای مستندنشده تکیه نکنید. پیش‌فرض‌های تولید و مسیرها در `config/module-generator.php` قرار دارند و فلگ‌های CLI در هر اجرا آن‌ها را override می‌کنند.

تنظیم Environment-backed فعلی پکیج خارج از گزینه‌های Swagger:

```dotenv
MODULE_GENERATOR_LOG_CHANNEL=
```

جزئیات کامل در [راهنمای پیکربندی](./configuration.md) آمده است.

## دستورهای مرتبط

پکیج این commandها را هم ثبت می‌کند:

```text
make:swagger
swagger:init
swagger:config
swagger:generate
swagger:ui
```

`make:swagger` برای سازگاری با مسیر قدیمی نگه داشته شده و برای workflow جدید JSON/OpenAPI بهتر است از `swagger:generate` استفاده شود.

</div>
