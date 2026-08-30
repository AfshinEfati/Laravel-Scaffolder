# Installation

[🇮🇷 فارسی](../fa/installation.md){ .language-switcher }

Get up and running with Laravel Scaffolder by following the checklist below.

## Requirements

- PHP 8.1 – 8.5
- Laravel 10.x – 13.x (the package service provider is auto-discovered)
- Database connection configured for running generated feature tests
- (Optional for Swagger annotations) Install `darkaonline/l5-swagger` **or** `zircote/swagger-php` if you plan to use the `--swagger` flag.

## 1. Require the package

```bash
composer require efati/laravel-scaffolder
```

Composer auto-discovers `ModuleGeneratorServiceProvider`, which exposes the `make:module` command and package helpers.

## 2. Publish configuration and helpers

```bash
php artisan vendor:publish
```

When the interactive prompt appears, select `Efati\ModuleGenerator\ModuleGeneratorServiceProvider` and then the `module-generator` tag. This copies:

- `config/module-generator.php` – adjust namespaces, paths, and default toggles here.
- Base repository/service classes plus the `ApiResponseHelper` helper used by generated controllers and resources.

Publishing is explicit: installing the package does not write these files into your application automatically.

Keep the configuration file under version control so every environment shares the same structure.

## 3. (Optional) Publish stub templates

```bash
php artisan vendor:publish
```

Choose the same service provider and pick the `module-generator-stubs` tag. Stubs are exported to `resources/stubs/module-generator` and override the package defaults on every generation run. Update them to inject traits, logging, or naming conventions that suit your organisation.

## 4. Prepare the environment

- Ensure your `.env` database settings are correct. Generated feature tests run against your configured connection instead of forcing SQLite.
- Commit the published base classes if you plan to customise them—future module runs expect these files to exist.
- If generated providers need to be registered manually in your Laravel version/application structure, keep that registration under version control.

With the prerequisites complete you can jump to the [quickstart guide](quickstart.md) for command recipes and inline schema examples.
