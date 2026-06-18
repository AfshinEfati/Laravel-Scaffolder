---
title: Introduction
description: Laravel Scaffolder generates a complete, configurable feature stack for Laravel applications.
---

# Laravel Scaffolder

Laravel Scaffolder generates a complete, configurable feature stack for Laravel applications: repositories, services, DTOs, actions, policies, controllers, form requests, API resources, providers, feature tests, and OpenAPI documentation.

## Requirements

- PHP `^8.1`
- Laravel `^10.0` or `^11.0`
- Composer 2

## Installation

Install the package:

```bash
composer require efati/laravel-scaffolder
```

Laravel package discovery registers the service provider automatically. Publish the base classes and configuration when you want application-level copies:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```
