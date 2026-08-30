---
title: Route-Based OpenAPI & Swagger UI
description: Generate OpenAPI JSON from Laravel routes and serve the bundled Swagger UI
---

# Route-Based OpenAPI & Swagger UI

Laravel Scaffolder includes a dependency-free route-based OpenAPI workflow alongside the per-module `--swagger` generation option.

## Two Swagger Workflows

### Module documentation

When generating a module, use `--swagger` when you want the module generator to create its Swagger/OpenAPI documentation output:

```bash
php artisan make:module Product --api --swagger \
  --fields="name:string,price:decimal(10,2)"
```

### Route-based JSON specification

Use `swagger:generate` to scan the application's registered API routes and create an OpenAPI 3 JSON specification:

```bash
php artisan swagger:generate
```

The route-based command does not require L5-Swagger or swagger-php.

## Recommended Workflow

Initialize the bundled UI assets once:

```bash
php artisan swagger:init
```

Generate the specification:

```bash
php artisan swagger:generate
```

Start the standalone Swagger UI server:

```bash
php artisan swagger:ui
```

By default the UI is served on:

```text
http://localhost:8000
```

You can regenerate the specification immediately before serving:

```bash
php artisan swagger:ui --refresh
```

## `swagger:init`

```text
swagger:init
  --force    Overwrite existing UI files
```

The command prepares the bundled Swagger UI files under `storage/swagger-ui`. An existing generated `swagger.json` is preserved when UI assets are refreshed.

## `swagger:generate`

```text
swagger:generate
  --output=     Custom output path for swagger.json
  --title=      API title (default: API Documentation)
  --version=    API version (default: 1.0.0)
  --host=       Override the server URL
```

Examples:

```bash
php artisan swagger:generate \
  --title="Store API" \
  --version="2.0.0" \
  --host="https://api.example.com"
```

Custom output path:

```bash
php artisan swagger:generate --output=storage/api/openapi.json
```

Without `--output`, the path comes from the package Swagger spec configuration.

## `swagger:ui`

```text
swagger:ui
  --port=8000       Port to serve on
  --host=localhost  Host/IP to bind to
  --refresh         Run swagger:generate before serving
```

Examples:

```bash
php artisan swagger:ui --port=8080
php artisan swagger:ui --host=127.0.0.1 --port=8080 --refresh
```

For safety, the command only accepts `localhost` or valid IP addresses as the bind host.

## `swagger:config`

Inspect the current configuration:

```bash
php artisan swagger:config --show
```

Export supported settings in `.env` format:

```bash
php artisan swagger:config --export-env
```

Supported command options:

```text
--show
--export-env
--theme=vanilla|tailwind|dark
--primary-color=
--secondary-color=
--title=
--reset
```

Running `swagger:config` without options opens its interactive mode.

## Configuration

Swagger configuration lives under `swagger` in `config/module-generator.php`. The shipped config controls:

- UI theme, colors and fonts
- Dark-mode behavior
- Display title/options
- Standalone server host/port
- Specification output path and filename
- Authentication middleware and OpenAPI security schemes

Common environment variables include:

```dotenv
SWAGGER_THEME=vanilla
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_AUTH_MIDDLEWARE=auth,auth:api,auth:sanctum
```

See the [configuration guide](./configuration.md) for the current structure.

## Legacy Command

`make:swagger` remains registered for backward compatibility with the older Swagger generation flow. For the route-based JSON workflow documented on this page, use `swagger:generate`.

## What Is Not Provided

Laravel Scaffolder does not register `generate:swagger`, `swagger:export`, or `swagger:docs` commands. To produce a JSON file, use `swagger:generate` and its `--output` option.
