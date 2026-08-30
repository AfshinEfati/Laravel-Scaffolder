# CLI Reference

Quick reference for the `make:module` command and its current options.

## Command

```bash
php artisan make:module {name} [options]
```

`name` is converted to StudlyCase, for example `Product`, `BlogPost`, or `OrderItem`.

## Options

| Option | Shortcut | Purpose |
| --- | --- | --- |
| `--controller=Subdir` | `-c` | Generate/place the controller in an optional subfolder. |
| `--api` | — | Use an API-style controller. |
| `--requests` | `-r` | Generate Store/Update form requests. |
| `--tests` | `-t` | Force feature-test generation on. |
| `--no-controller` | `-nc` | Skip controller generation. |
| `--no-resource` | `-nr` | Skip API resource generation. |
| `--no-dto` | `-nd` | Skip DTO generation. |
| `--no-test` | `-nt` | Skip feature-test generation. |
| `--no-provider` | `-np` | Skip module service-provider generation. |
| `--actions` | — | Generate the action layer. |
| `--no-actions` | — | Skip action generation. |
| `--policy` | — | Generate a CRUD policy. |
| `--no-policy` | — | Skip policy generation. |
| `--swagger` | `-sg` | Generate Swagger/OpenAPI documentation. |
| `--no-swagger` | — | Skip Swagger/OpenAPI generation. |
| `--all` | `-a` | Generate the full module stack. |
| `--full` | `-f` | Alias for full-stack generation. |
| `--from-migration=` | `-fm` | Infer fields/relations from a migration path or hint. |
| `--fields=` | — | Provide inline schema metadata. |
| `--force` | — | Overwrite generated files that already exist. |

> `-a` means `--all` and `-f` means `--full`. They are **not** shortcuts for `--api` or `--force`.

## Common Examples

### Full API module

```bash
php artisan make:module Product --all \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean"
```

### API module with selected features

```bash
php artisan make:module Product \
  --api --requests --tests --actions --policy --swagger \
  --fields="name:string:unique,price:decimal(10,2)"
```

### Generate from an existing migration

```bash
php artisan make:module Product --api --from-migration
```

Or provide a migration path/hint:

```bash
php artisan make:module Product \
  --from-migration=database/migrations/2024_01_15_create_products_table.php
```

### Minimal generation

```bash
php artisan make:module Product \
  --no-controller --no-resource --no-test --no-provider
```

## Inline Field Syntax

Fields use a comma-separated `name:type:modifier` style:

```bash
php artisan make:module Product --fields="name:string:unique,price:decimal(10,2):nullable,is_active:boolean"
```

Common schema examples include:

```text
name:string:unique
price:decimal(10,2):nullable
metadata:json:nullable
user_id:foreignId:constrained(users)
```

For schema parsing details, see the [schema-aware generation guide](./features/schema-aware-generation.md).

## What Can Be Generated

Depending on flags and configuration, a module can include:

- Repository implementation and contract
- Service implementation and contract
- DTO
- Controller
- Store/Update form requests
- API resource
- Action layer
- Policy
- Module service provider
- Feature test
- Swagger/OpenAPI documentation

The action layer currently contains a shared `BaseAction` plus module actions for List, Show, Create, Update, Delete, and ListWithRelations.

## Output Locations

Output paths are controlled by `config/module-generator.php`. With the shipped defaults, generated application files are distributed across directories such as:

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

The generator does not use a mandatory `app/Modules/{Module}` layout; paths are configurable.

## Provider Registration

When module-provider generation is enabled, Laravel Scaffolder creates the provider and attempts to register it automatically:

1. `bootstrap/providers.php`, when that file exists.
2. Otherwise, the `providers` array in `config/app.php`, when available.

## Package Configuration

Do not rely on undocumented overwrite/test environment variables. Generation defaults and paths live in `config/module-generator.php`, and CLI flags override them per run.

The currently shipped environment-backed package setting outside Swagger presentation/server/spec options is:

```dotenv
MODULE_GENERATOR_LOG_CHANNEL=
```

See the [configuration guide](./configuration.md) for the current config structure.

## Related Commands

Laravel Scaffolder also registers these package commands:

```text
make:swagger
swagger:init
swagger:config
swagger:generate
swagger:ui
```

`make:swagger` is retained as the legacy Swagger command; new JSON-based OpenAPI workflows should use `swagger:generate`.
