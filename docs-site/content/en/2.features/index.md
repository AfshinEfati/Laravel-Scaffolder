---
title: Module Command
---

# Module Command

The `make:module` command is the main entry point for generating your feature stack.

```text
php artisan make:module {name} [options]
```

## Options

| Option | Alias | Purpose |
| --- | --- | --- |
| `--api` | — | Generate an API controller; also enables requests and actions. |
| `--all` | `-a` | Generate the complete module stack. |
| `--fields=...` | — | Supply inline schema metadata. |
| `--from-migration=...` | `-fm` | Read field metadata from a migration. |
| `--force` | — | Overwrite existing output files. |

## Usage Examples

### 1. Simple API Module
Generate an API-focused module with validation and actions:
```bash
php artisan make:module Order --api
```

### 2. Full Stack with Inline Fields
Generate everything without an existing model:
```bash
php artisan make:module Product --all \
  --fields="name:string,price:decimal(10,2),is_active:boolean"
```

### 3. Update Documentation Only
If you only want to generate or update the Swagger documentation for a module:
```bash
php artisan make:module Product --swagger
```

## Generated Files
When you run with `--all`, the following files are typically created:
- **Repository**: `app/Repositories/Eloquent/ProductRepository.php`
- **Service**: `app/Services/ProductService.php`
- **DTO**: `app/DTOs/ProductDTO.php`
- **Controller**: `app/Http/Controllers/Api/V1/ProductController.php`
- **Actions**: `app/Actions/Product/CreateProductAction.php` (etc)
- **Test**: `tests/Feature/ProductTest.php`
