# Laravel Scaffolder

A Laravel package for generating complete, production-friendly feature stacks from a single Artisan command.

Instead of manually creating the same repositories, services, DTOs, actions, policies, controllers, requests, resources, providers, tests, and API documentation for every feature, Laravel Scaffolder generates the structure for you with configurable options.

## What It Generates

Depending on the selected options, the package can generate:

- Repositories and repository contracts
- Services and service contracts
- DTOs
- CRUD actions
- Policies
- Controllers
- Form requests
- API resources
- Service providers and bindings
- Feature tests
- OpenAPI / Swagger documentation

## Installation

```bash
composer require efati/laravel-scaffolder
```

Laravel package discovery registers the service provider automatically.

## Quick Start

Create a model and generate a complete module:

```bash
php artisan make:model Product -m
php artisan make:module Product --all
```

Generate an API-oriented stack:

```bash
php artisan make:module Product --api
```

Generate directly from inline schema metadata:

```bash
php artisan make:module Product --api \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean"
```

Generated code follows a layered structure built around repositories, services, DTOs, actions, requests, resources, policies, and providers.

## Compatibility

| Requirement | Supported |
| --- | --- |
| PHP | 8.1 – 8.5 |
| Laravel | 10 – 13 |
| Composer | 2.x |

## Documentation

Complete documentation, command options, examples, configuration, Swagger/OpenAPI usage, Jalali date support, testing, upgrading, troubleshooting, and contribution guides are maintained on the project documentation site.

- **English:** https://afshinefati.github.io/Laravel-Scaffolder/en/
- **فارسی:** https://afshinefati.github.io/Laravel-Scaffolder/fa/

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
```

## Testing

```bash
composer test
```

## Why This Package Exists

Laravel applications often repeat the same architectural setup for every new feature. This package turns that repetitive work into a consistent, configurable generation workflow while keeping the generated code explicit and editable inside the application.

## License

Laravel Scaffolder is open-source software licensed under the MIT License.
