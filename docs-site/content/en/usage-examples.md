---
title: Usage Examples
description: Practical Laravel Scaffolder command recipes
---

# Usage Examples

These examples use the current `make:module` command and shipped configuration.

## Start with a Model and Migration

Laravel Scaffolder generates the application layers around a model; it does not create the Eloquent model or database migration itself.

```bash
php artisan make:model Product -m
```

Define the migration/model as needed, then generate the module layers.

## Complete API Stack from Inline Fields

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

Depending on configuration, this can generate repositories, services, DTOs, requests, resources, actions, a policy, controller, provider, tests, and OpenAPI documentation.

## Use an Existing Migration

Let the generator infer field metadata from a migration:

```bash
php artisan make:module Product --api --from-migration
```

Or provide a migration path/hint explicitly:

```bash
php artisan make:module Product \
  --api \
  --from-migration=database/migrations/2026_01_15_create_products_table.php
```

## Generate the Full Stack

```bash
php artisan make:module Product --all \
  --fields="name:string,price:decimal(10,2)"
```

The `-a` shortcut maps to `--all`:

```bash
php artisan make:module Product -a \
  --fields="name:string,price:decimal(10,2)"
```

## Generate Without DTOs

Controllers, services, and actions can use array payloads when DTO generation is disabled:

```bash
php artisan make:module Product \
  --api --requests --actions \
  --no-dto \
  --fields="name:string,price:decimal(10,2)"
```

## Skip Selected Components

There is no `--only=` option. Use the available negative flags to remove components from a generation run:

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

Repository and service layers are core outputs of `make:module` and are generated on a normal run.

## Put a Controller in a Subfolder

```bash
php artisan make:module Product \
  --api \
  --controller=Admin \
  --fields="name:string"
```

The root namespace and output directories are controlled in `config/module-generator.php`; a module name such as `Admin\\Dashboard` is not the mechanism for changing the package namespace.

## Overwrite Generated Files Intentionally

Existing generated files are skipped by default. Use `--force` when you intentionally want them replaced:

```bash
php artisan make:module Product --api --force \
  --fields="name:string,price:decimal(10,2)"
```

## Customize Templates

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Published templates live at:

```text
resources/stubs/module-generator/
```

Edit those templates and run `make:module` again (with `--force` when replacing existing generated files).

## Run Generated Feature Tests

When test generation is enabled:

```bash
php artisan test tests/Feature/ProductCrudTest.php
```

The exact test path is configurable under `tests.feature` in `config/module-generator.php`.

## Next

- [Quickstart](./quickstart.md)
- [CLI reference](./reference.md)
- [Configuration](./configuration.md)
- [Schema-aware generation](./features/schema-aware-generation.md)
