# شروع سریع

<div dir="rtl" markdown="1">

اولین ماژول خود را در کمتر از پنج دقیقه بسازید.

## مرحلهٔ ۱: نصب بسته

```bash
composer require efati/laravel-scaffolder
```

Service Provider پکیج به‌صورت خودکار شناسایی می‌شود. قبل از تولید ماژول‌ها، پیکربندی و کلاس‌های پایه را یک‌بار منتشر کنید:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

## مرحلهٔ ۲: تعریف Schema (یکی را انتخاب کنید)

### گزینهٔ الف: فیلدهای درون‌خطی

```bash
php artisan make:module Product \
  --fields="name:string:unique,price:decimal(10,2),stock:integer,is_active:boolean"
```

### گزینهٔ ب: از مایگریشن موجود

```bash
# اگر مایگریشن موجود است:
php artisan make:module Product --from-migration

# یا مسیر مایگریشن را مشخص کنید:
php artisan make:module Product --from-migration=database/migrations/2024_01_15_create_products_table.php
```

## مرحلهٔ ۳: تولید ماژول

برای یک **ماژول API کامل**:

```bash
php artisan make:module Product \
  --api \
  --requests \
  --tests \
  --swagger \
  --fields="name:string:unique,price:decimal(10,2),stock:integer,is_active:boolean"
```

بسته به فلگ‌ها و تنظیمات، این موارد قابل تولید هستند:

✅ ریپازیتوری + Interface
✅ سرویس + Interface
✅ DTO
✅ کنترلر API
✅ فرم‌های Request
✅ API Resource
✅ لایه Actions
✅ Policy
✅ تست‌های Feature
✅ Service Provider و ثبت آن در اپلیکیشن
✅ مستندات OpenAPI

## مرحلهٔ ۴: ثبت مسیرها

```php
// routes/api.php
Route::apiResource('products', ProductController::class);

// یا با پیشوند دلخواه:
Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
});
```

## مرحلهٔ ۵: تست‌کردن

```bash
php artisan test tests/Feature/ProductCrudTest.php
```

برای مولد OpenAPI مبتنی بر Route خود پکیج، [مستندات Swagger](./route-based-swagger.md) را ببینید.

## شخصی‌سازی فایل‌های تولیدشده (اختیاری)

برای قالب‌های دلخواه:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

فایل‌ها را در `resources/stubs/module-generator/` ویرایش کنید و بعد با `--force` دوباره تولید کنید:

```bash
php artisan make:module Product --api --force
```

## ساختار معمول فایل‌های تولیدشده

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

فایل‌های دقیق به فلگ‌ها و تنظیماتی که انتخاب می‌کنید بستگی دارند.

## گام‌های بعدی

- [تولید ماژول‌ها](./features/generating-modules.md) را برای تمام گزینه‌های دستور بخوانید
- درباره [ویژگی‌های Schema-Aware](./features/schema-aware-generation.md) بیاموزید
- الگوهای [لایهٔ Action](./features/action-layer.md) را بررسی کنید
- [مرجع CLI](./reference.md) را برای تمام گزینه‌ها مشاهده کنید

</div>
