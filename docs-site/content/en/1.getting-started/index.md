---
title: Installation
---

# Installation

Laravel Scaffolder requires PHP 8.1+ and Laravel 10 or 11.

## Step 1: Install the package

Install the package via composer:

```bash
composer require efati/laravel-scaffolder
```

## Step 2: Publish Assets

Laravel package discovery registers the service provider automatically. You should publish the base classes and configuration to customize them for your application:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

## Step 3: Optional Stubs

If you want to customize the generated code templates, publish the stubs:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Published stubs will be located in `resources/stubs/module-generator`.
