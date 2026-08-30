# Configuration

Customize Laravel Scaffolder once so generated modules follow the same project conventions across your team.

## Publish the Configuration

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

This publishes `config/module-generator.php` together with the package's default base classes and helper.

## Base Namespace

The root namespace is configured with `base_namespace`:

```php
'base_namespace' => 'App',
```

Use another PSR-4 root only when your application autoloading is configured for it.

## Generated Paths

Paths under `paths` are relative to `app/`:

```php
'paths' => [
    'repository' => [
        'eloquent' => 'Repositories/Eloquent',
        'contracts' => 'Repositories/Contracts',
    ],
    'service' => [
        'concretes' => 'Services',
        'contracts' => 'Services/Contracts',
    ],
    'dto' => 'DTOs',
    'provider' => 'Providers',
    'controller' => [
        'api' => 'Http/Controllers/Api/V1',
        'web' => 'Http/Controllers',
    ],
    'resource' => 'Http/Resources',
    'form_request' => 'Http/Requests',
    'actions' => 'Actions',
    'docs' => 'Docs',
],
```

Feature tests use a project-root-relative path instead:

```php
'tests' => [
    'feature' => 'tests/Feature',
],
```

## Command Defaults

The shipped configuration currently defines these generation defaults:

```php
'defaults' => [
    'with_controller' => true,
    'with_form_requests' => false,
    'with_unit_test' => true,
    'with_resource' => true,
    'with_dto' => true,
    'with_provider' => true,
    'with_actions' => false,
    'controller_middleware' => [],
    'controller_type' => 'api', // 'api' or 'web'
],
```

CLI flags override these values for an individual generation run. The command also understands optional `with_policy` and `with_swagger` defaults when you add them to your published configuration.

## Swagger UI and OpenAPI

Swagger settings live under the `swagger` key. The current configuration includes:

- `theme` – `vanilla`, `tailwind`, or `dark`
- `colors` – UI color variables
- `fonts` – regular and monospace font stacks
- `dark_mode` – enabled/default/persistence options
- `display` – title, description, model/example visibility, auth persistence
- `server` – local host and port settings
- `spec` – output directory, filename, and access setting for `swagger.json`
- `security` – auth middleware, default scheme, and OpenAPI security schemes

Example security configuration:

```php
'security' => [
    'auth_middleware' => env(
        'SWAGGER_AUTH_MIDDLEWARE',
        'auth,auth:api,auth:sanctum'
    ),
    'default' => 'bearerAuth',
    'secure_spec' => env('SWAGGER_SECURE_SPEC', false),
    'schemes' => [
        'bearerAuth' => [
            'type' => 'http',
            'scheme' => 'bearer',
            'bearer_format' => 'JWT',
        ],
    ],
],
```

## Environment Variables

The shipped configuration reads environment variables for Swagger presentation/server/spec settings and one module-generator logging setting. Common examples include:

```dotenv
SWAGGER_THEME=vanilla
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_AUTH_MIDDLEWARE=auth,auth:api,auth:sanctum
MODULE_GENERATOR_LOG_CHANNEL=
```

The published config is the source of truth for the complete list of supported environment variables.

## Generated Provider Registration

When provider generation is enabled, Laravel Scaffolder creates a module service provider and attempts to register it in the application:

- If `bootstrap/providers.php` exists, it adds the provider there.
- Otherwise, if `config/app.php` contains a `providers` array, it adds the provider to that array.

This keeps the behavior compatible with different Laravel application structures instead of relying on one hard-coded framework version.

## Publish Custom Stubs

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Published templates are stored in `resources/stubs/module-generator/`. Edit those files when you want generated code to follow project-specific conventions.
