# Quickstart

Get up and running with your first generated module in under 5 minutes.

## Step 1: Install the Package

```bash
composer require efati/laravel-scaffolder
```

The package service provider is discovered automatically. Publish the default configuration and base classes once before generating modules:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

## Step 2: Define Your Schema (Choose One)

### Option A: Inline Fields

```bash
php artisan make:module Product \
  --fields="name:string:unique,price:decimal(10,2),stock:integer,is_active:boolean"
```

### Option B: From Migration

```bash
# If migration exists:
php artisan make:module Product --from-migration

# Or specify migration path:
php artisan make:module Product --from-migration=database/migrations/2024_01_15_create_products_table.php
```

## Step 3: Generate the Module

For a **complete API module**:

```bash
php artisan make:module Product \
  --api \
  --requests \
  --tests \
  --swagger \
  --fields="name:string:unique,price:decimal(10,2),stock:integer,is_active:boolean"
```

This can generate:

✅ Repository + Interface
✅ Service + Interface
✅ DTO
✅ API Controller
✅ Form Requests
✅ API Resource
✅ Action Layer
✅ Policy
✅ Feature Tests
✅ Service Provider (registered in the application)
✅ OpenAPI Documentation

## Step 4: Register Routes

```php
// routes/api.php
Route::apiResource('products', ProductController::class);

// Or with custom prefix:
Route::prefix('v1')->group(function () {
    Route::apiResource('products', ProductController::class);
});
```

## Step 5: Test It

```bash
# Run generated feature tests
php artisan test tests/Feature/ProductCrudTest.php
```

For the package's route-based OpenAPI generator, see the [Swagger documentation](./route-based-swagger.md).

## Customize Generated Files (Optional)

Publish stubs for custom templates:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Edit files in `resources/stubs/module-generator/` then regenerate with `--force`:

```bash
php artisan make:module Product --api --force
```

## Typical Generated Structure

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

The exact files depend on the flags and configuration you choose.

## Next Steps

- Explore [generating modules](./features/generating-modules.md) for all available options
- Learn about [schema-aware features](./features/schema-aware-generation.md)
- Review [action layer patterns](./features/action-layer.md)
- Check the [CLI reference](./reference.md) for complete command options
