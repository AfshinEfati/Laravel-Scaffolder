# Laravel Scaffolder

Laravel Scaffolder generates a complete, configurable feature stack for Laravel applications: repositories, services, DTOs, actions, policies, controllers, form requests, API resources, providers, feature tests, and OpenAPI documentation.

This README is the single source of project documentation. Command behavior, configuration, Swagger usage, Jalali dates, testing, upgrading, contribution rules, and troubleshooting are maintained here.

**Languages:** [English](#english-documentation) · [فارسی](#مستندات-فارسی)

<a id="english-documentation"></a>

## English Documentation

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Module Command](#module-command)
- [Schema Sources](#schema-sources)
- [Generated Structure](#generated-structure)
- [Configuration](#configuration)
- [Runtime APIs](#runtime-apis)
- [Swagger and OpenAPI](#swagger-and-openapi)
- [Jalali Dates](#jalali-dates)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Upgrading and Changelog](#upgrading-and-changelog)
- [Contributing and Security](#contributing-and-security)

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

Publish customizable generator stubs:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Published stubs live in `resources/stubs/module-generator`.

## Quick Start

The command needs an existing Eloquent model unless schema metadata is supplied with `--fields` or `--from-migration`.

```bash
php artisan make:model Product -m
php artisan make:module Product
```

Generate an explicit API stack:

```bash
php artisan make:module Product --api
```

Generate the complete stack, including Swagger, actions, policy, requests, resources, tests, DTO, provider, service, repository, and controller:

```bash
php artisan make:module Product --all
```

Overwrite existing generated files:

```bash
php artisan make:module Product --all --force
```

Generate without an existing model or migration:

```bash
php artisan make:module Product --api \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean,category_id:integer:fk=categories.id"
```

## Module Command

```text
php artisan make:module {name} [options]
```

### Options

| Option | Alias | Purpose |
| --- | --- | --- |
| `--controller=Subdir` | `-c` | Put the controller in a custom subdirectory. |
| `--api` | — | Generate an API controller; also enables requests and actions unless explicitly disabled. |
| `--requests` | `-r` | Generate store and update form requests. |
| `--tests` | `-t` | Force feature-test generation. |
| `--actions` | — | Generate CRUD action classes. |
| `--policy` | — | Generate a policy class. |
| `--swagger` | `-sg` | Generate module OpenAPI/PHPDoc documentation. Used alone, it runs in Swagger-only mode. |
| `--all` | `-a` | Generate the complete module stack. |
| `--full` | `-f` | Same complete-stack behavior as `--all`. |
| `--from-migration=...` | `-fm` | Read field metadata from a migration path or hint. |
| `--fields=...` | — | Supply inline schema metadata. |
| `--force` | — | Overwrite existing output files. |
| `--no-controller` | `-nc` | Skip the controller. |
| `--no-resource` | `-nr` | Skip the API resource. |
| `--no-dto` | `-nd` | Skip the DTO. |
| `--no-test` | `-nt` | Skip the feature test. |
| `--no-provider` | `-np` | Skip provider generation and automatic bindings. |
| `--no-actions` | — | Skip action classes. |
| `--no-policy` | — | Skip the policy. |
| `--no-swagger` | — | Skip Swagger documentation. |

`--api` has no short alias. The `-a` alias belongs to `--all`, `-f` belongs to `--full`, and `--force` has no short alias.

### Common Recipes

```bash
# API module with generated validation and actions
php artisan make:module Order --api

# Web controller in a custom folder
php artisan make:module Invoice --controller=Admin/Accounting --no-actions

# Full stack, preserving existing files
php artisan make:module Customer --all

# Full stack, replacing existing files
php artisan make:module Customer --all --force

# Swagger file only for an existing module
php artisan make:module Product --swagger

# Policy without Swagger
php artisan make:module Product --policy --no-swagger

```

## Schema Sources

The generator merges metadata from these sources:

1. Inline `--fields` definitions.
2. A migration selected with `--from-migration`.
3. Runtime Eloquent metadata and database columns.
4. Model `$fillable` and casts as fallback metadata.

Inline schema has the form `name:type:modifier:modifier`. Commas separate fields; commas inside parentheses such as `decimal(10,2)` are preserved.

### Supported Types

Common normalized types include:

- `string`, `char`, `varchar`
- `text`, `mediumText`, `longText`
- `integer`, `bigInteger`, `foreignId`
- `decimal`, `numeric`, `float`, `double`
- `boolean`
- `date`, `datetime`, `timestamp`
- `json`, `array`
- `uuid`, `email`, `url`

### Supported Modifiers

- `nullable`, `null`, or `optional`
- `required`, `notnull`, or `not-null`
- `unique` or `uniq`
- `fk=table.column`
- `foreign=table.column`
- `references=table.column`

Examples:

```bash
php artisan make:module Subscription --api \
  --fields="tenant_id:integer:fk=tenants.id,plan:string,status:string,expires_at:datetime:nullable"

php artisan make:module Product --api \
  --fields="sku:string:unique,price:decimal(12,2),metadata:json:nullable"

php artisan make:module Product --api \
  --from-migration=create_products_table
```

Invalid PHP identifiers, reserved keywords, and duplicate field names stop generation instead of producing invalid PHP.

## Generated Structure

Depending on options and configuration, a module can create:

```text
app/
├── Actions/Product/
├── DTOs/ProductDTO.php
├── Docs/ProductDoc.php
├── Http/Controllers/Api/V1/ProductController.php
├── Http/Requests/Product/
│   ├── StoreProductRequest.php
│   └── UpdateProductRequest.php
├── Http/Resources/ProductResource.php
├── Policies/ProductPolicy.php
├── Providers/ProductServiceProvider.php
├── Repositories/
│   ├── Contracts/ProductRepositoryInterface.php
│   └── Eloquent/ProductRepository.php
└── Services/
    ├── Contracts/ProductServiceInterface.php
    └── ProductService.php

tests/Feature/ProductTest.php
```

### Responsibilities

- **Repository:** persistence and query access.
- **Service:** application/business operations.
- **DTO:** typed request data transfer.
- **Action:** focused CRUD use cases with generated logging support.
- **Form Request:** validation rules inferred from schema metadata.
- **Resource:** API serialization, casts, dates, and loaded relations.
- **Controller:** API or web orchestration.
- **Provider:** repository/service bindings and provider registration.
- **Policy:** authorization skeleton.
- **Feature Test:** generated CRUD test scaffold.
- **Swagger Doc:** module-level OpenAPI-compatible PHPDoc annotations.

Existing files are skipped unless `--force` is supplied. Empty or failed generator results cause the command to return a failure status rather than reporting false success.

## Configuration

The canonical configuration file is `config/module-generator.php` after publishing.

### Paths

```php
'base_namespace' => 'App',

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

'tests' => [
    'feature' => 'tests/Feature',
],
```

Generated namespaces follow configured paths. If `dto` changes to `Data`, generated DTO namespaces and references change to `App\Data` as well.

### Defaults

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
    'controller_type' => 'api',
],
```

Because the default controller type is `api`, the default command also enables form requests and actions. Set `controller_type` to `web` when the project primarily generates web controllers.

Set the logging channel used by generated actions through:

```env
MODULE_GENERATOR_LOG_CHANNEL=stack
```

## Runtime APIs

Publishing the `module-generator` tag installs reusable base classes and `App\Helpers\ApiResponseHelper`. Generated modules extend or depend on these classes.

### Repository API

Generated repositories extend `App\Repositories\Eloquent\BaseRepository` and implement a generated contract.

Core methods:

| Method | Result |
| --- | --- |
| `getAll()` | Returns all records after applying criteria. |
| `find($id)` | Returns one model or `null`. |
| `store($data)` | Creates and returns a model. |
| `update($id, $data)` | Updates a model and returns a boolean. |
| `delete($id)` | Deletes a model and returns a boolean. |
| `findDynamic(...)` | Applies dynamic filters and returns the first result. |
| `getByDynamic(...)` | Applies dynamic filters and returns a collection. |
| `pushCriteria($criteria)` | Adds a query criterion. |
| `popCriteria($criteria)` | Removes a criterion by object or class. |
| `skipCriteria()` | Temporarily disables criteria. |

Dynamic queries support `where`, eager-loaded `with`, `whereNot`, `whereIn`, `whereNotIn`, between/null filters, equivalent `orWhere` filters, and raw expressions.

```php
$products = $productRepository->getByDynamic(
    where: [['status', '=', 'active']],
    with: ['category'],
    whereIn: ['category_id', [1, 2, 3]],
    whereBetween: ['price', [100, 500]],
);

$product = $productRepository->findDynamic(
    where: [
        ['sku', '=', 'SKU-100'],
        ['is_active', '=', true],
    ],
);
```

Each condition accepts either one argument list, a list of argument lists, or an associative form supported by Laravel's query builder.

### Criteria Pattern

A criterion must expose `apply(Builder $query): Builder`. Implementing the published `CriteriaInterface` is recommended.

```php
namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

final class ActiveProductsCriteria
{
    public function apply(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
```

```php
$products = $productRepository
    ->pushCriteria(new ActiveProductsCriteria())
    ->getAll();

$productRepository->popCriteria(ActiveProductsCriteria::class);
$unfiltered = $productRepository->skipCriteria()->getAll();
```

Criteria may also be passed as class names with a parameterless constructor. The repository instantiates the class and uses its `apply()` method.

### Service API

Generated services wrap their repository and expose:

- `repository()`
- `index()` and `show($id)`
- `store($payload)`, `update($id, $payload)`, and `destroy($id)`
- `findDynamic(...)` and `getByDynamic(...)`
- repository method forwarding through `__call()`

When DTO generation is enabled, generated `store` and `update` methods accept the module DTO and convert it to an array. Otherwise they accept arrays.

```php
use App\DTOs\ProductDTO;
use App\Services\ProductService;
use Illuminate\Http\Request;

public function store(Request $request, ProductService $service)
{
    $product = $service->store(ProductDTO::fromRequest($request));

    return response()->json($product, 201);
}
```

### DTO API

Generated DTOs use promoted readonly properties inferred from schema metadata.

```php
$dto = ProductDTO::fromRequest($request);

$dto->name;
$dto->price;
$payload = $dto->toArray(); // Omits null properties.
```

DTO inference maps integer casts to `?int`, booleans to `?bool`, numeric casts to `?float`, arrays/JSON to `?array`, and other values to `?string`.

### Action API

Generated CRUD actions are invokable and also expose `execute()`. Exceptions are logged through the configured channel and rethrown.

```php
use App\Actions\Product\CreateProductAction;
use App\DTOs\ProductDTO;

public function store(Request $request, CreateProductAction $createProduct)
{
    $product = $createProduct(ProductDTO::fromRequest($request));

    return response()->json($product, 201);
}
```

Generated actions include list, list-with-relations, show, create, update, and delete operations.

### API Resources and Relations

Generated resources:

- Always include the model identifier.
- Format date/datetime attributes with `ApiResponseHelper::formatDates()`.
- Convert boolean/status attributes with `ApiResponseHelper::getStatus()`.
- Use `RelatedResource::collection()` for collection relations such as `hasMany` and `belongsToMany`.
- Use one resource instance for singular relations.

Load relations before returning the resource:

```php
$product = $productService->getByDynamic(
    where: [['id', '=', $id]],
    with: ['category', 'tags'],
)->first();

return new ProductResource($product);
```

### API Response Helper

The published `App\Helpers\ApiResponseHelper` provides:

```php
use App\Helpers\ApiResponseHelper;

return ApiResponseHelper::successResponse($product, 'Product created', 201);
return ApiResponseHelper::errorResponse('Validation failed', 422, $errors);
return ApiResponseHelper::unauthorized();
return ApiResponseHelper::forbidden();
return ApiResponseHelper::notFound('Product not found');
```

Additional transformations:

```php
ApiResponseHelper::formatDates(now());
// ['date' => '2026-06-18', 'time' => '...', 'fa_date' => '...', 'iso' => '...']

ApiResponseHelper::getStatus(true);
// ['name' => 'active', 'fa_name' => 'فعال', 'code' => 1]
```

`getCabinType()` is a domain-oriented convenience mapping included in the published helper and can be removed or customized after publishing.

### Enum Helper

Use `EnumHelperTrait` in backed enums to expose consistent English/Persian metadata. Define `faName()` when Persian labels are needed.

```php
namespace App\Enums;

use Efati\ModuleGenerator\Enums\Concerns\EnumHelperTrait;

enum OrderStatus: string
{
    use EnumHelperTrait;

    case Pending = 'pending';
    case Paid = 'paid';

    public function faName(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Paid => 'پرداخت‌شده',
        };
    }
}
```

```php
OrderStatus::toList();
OrderStatus::toMap();
OrderStatus::findByValue('paid');
OrderStatus::findByName('Paid');
```

Each returned item contains `name`, `fa_name`, and `code`.

### Policies, Providers, and Tests

- Generated policies contain standard model authorization methods and can be registered through normal Laravel policy discovery/configuration.
- Generated providers bind repository and service contracts to concrete classes and are inserted into `bootstrap/providers.php` or `config/app.php` when possible.
- Generated feature tests register an API resource route, infer payloads from factories/schema metadata, and cover CRUD behavior. Review generated payloads for domain-specific required data.

## Swagger and OpenAPI

The package contains two documentation paths:

1. `swagger:generate` creates an OpenAPI 3 JSON specification from Laravel routes.
2. `make:module --swagger` creates module PHPDoc annotations in the configured docs directory.

The JSON workflow is standalone and does not require `l5-swagger` or `swagger-php`.

### Standalone Workflow

```bash
# Copy the bundled UI to storage/swagger-ui
php artisan swagger:init

# Generate storage/swagger-ui/swagger.json
php artisan swagger:generate

# Serve the UI
php artisan swagger:ui
```

Open `http://localhost:8000` unless host or port configuration changes it.

Regenerate the specification before serving:

```bash
php artisan swagger:ui --refresh
```

### Swagger Commands

```bash
# Initialize or overwrite UI assets
php artisan swagger:init
php artisan swagger:init --force

# Generate JSON from routes
php artisan swagger:generate
php artisan swagger:generate --title="Store API" --version=2.0.0
php artisan swagger:generate --host=https://api.example.com
php artisan swagger:generate --output=storage/swagger-ui/internal.json

# Run the standalone server
php artisan swagger:ui --host=localhost --port=8000

# Inspect or update UI configuration
php artisan swagger:config --show
php artisan swagger:config --theme=dark --primary-color=#8b5cf6
php artisan swagger:config --title="Store API"
php artisan swagger:config --export-env
php artisan swagger:config --reset
```

`make:swagger` is the legacy route-to-PHPDoc generator and remains available for backward compatibility:

```bash
php artisan make:swagger --force
php artisan make:swagger --path=api/v1
php artisan make:swagger --controller=App\\Http\\Controllers\\Api
php artisan make:swagger --output=app/Docs
```

Prefer `swagger:generate` for the built-in JSON UI workflow.

### Laravel Routes for the UI

To serve the initialized UI and JSON through the Laravel application, register controller routes explicitly:

```php
use Efati\ModuleGenerator\Http\Controllers\SwaggerUIController;
use Illuminate\Support\Facades\Route;

Route::get('/api/docs', [SwaggerUIController::class, 'index']);
Route::get('/api/swagger.json', [SwaggerUIController::class, 'spec']);
```

Protect them with normal Laravel middleware when required:

```php
Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/api/docs', [SwaggerUIController::class, 'index']);
    Route::get('/api/swagger.json', [SwaggerUIController::class, 'spec']);
});
```

The package also exposes `RegistersSwaggerRoutes` for applications that prefer a reusable registrar:

```php
namespace App\Support;

use Efati\ModuleGenerator\Traits\RegistersSwaggerRoutes;

final class SwaggerRoutes
{
    use RegistersSwaggerRoutes;
}
```

```php
use App\Support\SwaggerRoutes;

SwaggerRoutes::registerSwaggerRoutes();
```

This registers `/docs` and `/docs/swagger.json` in the current route context. Wrap the call in a prefixed/middleware route group when a different URL or protection is required.

When `SWAGGER_SECURE_SPEC=true`, the specification endpoint also checks configured authentication guards and returns `403` for anonymous requests.

### Swagger Environment Variables

```env
SWAGGER_THEME=vanilla
SWAGGER_COLOR_PRIMARY=#3b82f6
SWAGGER_COLOR_PRIMARY_DARK=#1e40af
SWAGGER_COLOR_PRIMARY_LIGHT=#eff6ff
SWAGGER_COLOR_SECONDARY=#06b6d4
SWAGGER_FONT_FAMILY="system-ui, -apple-system, sans-serif"
SWAGGER_FONT_MONO="Fira Code, Courier New, monospace"
SWAGGER_DARK_MODE_ENABLED=true
SWAGGER_DARK_MODE_DEFAULT=auto
SWAGGER_DARK_MODE_PERSIST=true
SWAGGER_UI_TITLE="API Documentation"
SWAGGER_UI_DESCRIPTION="REST API Documentation"
SWAGGER_SHOW_MODELS=true
SWAGGER_SHOW_EXAMPLES=true
SWAGGER_PERSIST_AUTH=true
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_AUTH_MIDDLEWARE="auth,auth:api,auth:sanctum"
```

Available themes are `vanilla`, `tailwind`, and `dark`. The UI assets and generated specification share `storage/swagger-ui` by default.

### Module PHPDoc Generation

```bash
# Add documentation while generating the module
php artisan make:module Product --api --swagger

# Generate only the module documentation file
php artisan make:module Product --swagger
```

The output is compatible with optional OpenAPI annotation tooling, but external packages are not required for the built-in JSON workflow.

## Jalali Dates

`Goli` provides Jalali/Gregorian conversion while wrapping Carbon. `Verta` extends `Goli` and retains legacy aliases for backward compatibility.

```php
use Efati\ModuleGenerator\Support\Goli;
use Efati\ModuleGenerator\Support\Verta;

$date = Goli::now('Asia/Tehran');
$jalali = $date->toGoliDateString();
$jalaliWithTime = $date->toGoliDateTimeString();

$parsed = Goli::parseGoli('1403/01/01 08:30:00', 'Asia/Tehran');
$carbon = $parsed->toCarbon();

[$gy, $gm, $gd] = Goli::goliToGregorian(1403, 1, 1);
[$jy, $jm, $jd] = Goli::gregorianToGoli(2024, 3, 20);

$legacy = Verta::now();
$legacy->toJalaliDateString();
Verta::jalaliToGregorian(1403, 1, 1);
```

The global helper is also available:

```php
$date = goli(now(), 'Asia/Tehran');
$same = goli_date(now());
```

Useful APIs include:

- `now()`, `parse()`, `parseGoli()`, `instance()`, `create()`, and `fromTimestamp()`
- `setGoliDate()`, `setGoliDateTime()`, `addDays()`, and `subDays()`
- `toGoliDateString()`, `toGoliDateTimeString()`, `toGoliArray()`
- `goliToGregorian()`, `gregorianToGoli()`, `isLeapGoliYear()`
- `diffForHumans()`, `format()`, `copy()`, `timezone()`, `toCarbon()`
- `persianNumbers()` and `latinNumbers()`

Invalid Gregorian dates, invalid Jalali month lengths, non-leap Esfand 30, and invalid time components throw `InvalidArgumentException`.

The package also includes `GoliDateCast` and `HasGoliDates` for Eloquent integration.

Use the cast directly:

```php
use Efati\ModuleGenerator\Casts\GoliDateCast;

protected function casts(): array
{
    return [
        'published_at' => GoliDateCast::class,
    ];
}
```

Or configure multiple attributes through the trait:

```php
use Efati\ModuleGenerator\Support\HasGoliDates;

class Post extends Model
{
    use HasGoliDates;

    protected array $goliDates = ['published_at', 'expires_at'];
}
```

Read values are `Goli` instances; assigned `Goli`, Carbon, date objects, timestamps, or parseable strings are stored using the model's Gregorian date format. Runtime casts can be added with `$model->addGoliDateCast('approved_at')`.

## Testing

Install development dependencies and run:

```bash
composer install
composer test
```

Run syntax checks:

```bash
find src tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

On Windows with the supplied PHP path:

```powershell
Get-ChildItem src,tests -Recurse -Filter *.php |
  ForEach-Object { & 'C:\php-8.5\php.exe' -l $_.FullName }
```

The GitHub Actions PHP workflow validates supported PHP/Laravel combinations, lints source files, and runs PHPUnit.

Current focused tests cover:

- Jalali and Gregorian date validation.
- `diffForHumans()` behavior.
- legacy `Verta` compatibility.
- Swagger color palette generation and validation.

When changing generators, also test generated output inside a Laravel fixture and lint every generated PHP file.

## Troubleshooting

### Model Not Found

Create the model first or provide schema metadata:

```bash
php artisan make:model Product -m
php artisan make:module Product --api

# Or without a model
php artisan make:module Product --api --fields="name:string,price:decimal(10,2)"
```

### Existing Files Are Skipped

Use `--force` only when overwriting is intended:

```bash
php artisan make:module Product --all --force
```

### Stub Not Found

Republish stubs and clear application caches:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs \
  --force

php artisan optimize:clear
composer dump-autoload
```

### Provider Is Not Registered

Laravel 11 uses `bootstrap/providers.php`; older Laravel applications commonly use `config/app.php`. If automatic insertion is not possible, add the generated provider manually.

### Swagger UI Is Missing

```bash
php artisan swagger:init --force
php artisan swagger:generate
php artisan swagger:ui
```

Confirm that these files exist:

```text
storage/swagger-ui/index.html
storage/swagger-ui/swagger.json
```

### Generated Tests Fail

- Configure the test database in `.env.testing` or `phpunit.xml`.
- Ensure factories exist for required models.
- Run migrations before the suite.
- Check that route parameters match generated controller bindings.

### Custom Paths Produce Autoload Errors

Run `composer dump-autoload` after changing generated paths. Paths must remain under the application namespace configured by `base_namespace`.

## Upgrading and Changelog

The current release line is `7.x`. Before upgrading:

1. Review local changes in published configuration and stubs.
2. Update the package with Composer.
3. Republish configuration or stubs only when you intend to merge upstream changes.
4. Run `composer dump-autoload`, PHP lint, and the test suite.
5. Regenerate one representative module in a fixture before applying `--force` broadly.

```bash
composer update efati/laravel-scaffolder
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator \
  --force
```

Notable `7.x` capabilities include the built-in standalone Swagger UI, route-based OpenAPI JSON generation, schema-aware module generation, action and policy generation, configurable namespaces, improved generated tests, strict date validation, and backward-compatible `Verta` aliases.

Git history and GitHub releases are the authoritative detailed changelog.

## Contributing and Security

Contributions should remain focused, backward-compatible where practical, and covered by tests.

1. Fork the repository and create a focused branch.
2. Follow the existing PHP style and generator patterns.
3. Add or update tests for behavioral changes.
4. Run PHP lint and `composer test`.
5. Open a pull request describing behavior, generated output, and compatibility impact.

For bugs, include Laravel/PHP versions, the command used, relevant configuration, expected output, actual output, and a minimal reproduction.

Do not publish security vulnerabilities in public issues. Report them privately to the repository maintainer through GitHub security reporting or the contact method listed on the repository profile.

## License

Laravel Scaffolder is open-source software licensed under the MIT License. See `LICENSE`.

---

<a id="مستندات-فارسی"></a>

## مستندات فارسی

Laravel Scaffolder یک پکیج قابل‌تنظیم برای تولید لایه‌های کامل یک قابلیت در پروژه‌های Laravel است. این پکیج Repository، Service، DTO، Action، Policy، Controller، FormRequest، API Resource، Provider، تست Feature و مستندات OpenAPI را تولید می‌کند.

### فهرست فارسی

- [نیازمندی‌ها و نصب](#نیازمندیها-و-نصب)
- [شروع سریع](#شروع-سریع-فارسی)
- [فرمان تولید ماژول](#فرمان-تولید-ماژول)
- [تعریف Schema](#تعریف-schema)
- [فایل‌ها و لایه‌های تولیدی](#فایلها-و-لایههای-تولیدی)
- [تنظیمات](#تنظیمات-فارسی)
- [APIهای زمان اجرا](#apiهای-زمان-اجرا)
- [Swagger و OpenAPI](#swagger-و-openapi)
- [تاریخ جلالی](#تاریخ-جلالی)
- [تست و رفع اشکال](#تست-و-رفع-اشکال)
- [ارتقا، مشارکت و امنیت](#ارتقا-مشارکت-و-امنیت)

<a id="نیازمندیها-و-نصب"></a>

### نیازمندی‌ها و نصب

- PHP نسخه `8.1` یا بالاتر در محدوده `^8.1`
- Laravel نسخه `10` یا `11`
- Composer 2

```bash
composer require efati/laravel-scaffolder
```

Provider پکیج با package discovery لاراول ثبت می‌شود. برای انتشار config، کلاس‌های پایه و helper پاسخ API:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

برای انتشار stubها و شخصی‌سازی کد تولیدشده:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

stubهای منتشرشده در `resources/stubs/module-generator` قرار می‌گیرند.

<a id="شروع-سریع-فارسی"></a>

### شروع سریع فارسی

در حالت عادی مدل Eloquent باید وجود داشته باشد:

```bash
php artisan make:model Product -m
php artisan make:module Product
```

برای تولید صریح ساختار API:

```bash
php artisan make:module Product --api
```

برای تولید تمام لایه‌ها:

```bash
php artisan make:module Product --all
```

برای جایگزینی فایل‌های موجود:

```bash
php artisan make:module Product --all --force
```

بدون مدل یا migration نیز می‌توان schema را مستقیم تعریف کرد:

```bash
php artisan make:module Product --api \
  --fields="name:string:unique,price:decimal(10,2),is_active:boolean,category_id:integer:fk=categories.id"
```

<a id="فرمان-تولید-ماژول"></a>

### فرمان تولید ماژول

```text
php artisan make:module {name} [options]
```

| گزینه | نام کوتاه | کاربرد |
| --- | --- | --- |
| `--controller=Subdir` | `-c` | قرار دادن Controller در زیرمسیر دلخواه. |
| `--api` | — | تولید Controller نوع API و فعال‌کردن Request و Action مگر اینکه غیرفعال شوند. |
| `--requests` | `-r` | تولید Store و Update FormRequest. |
| `--tests` | `-t` | فعال‌کردن تولید تست Feature. |
| `--actions` | — | تولید Actionهای CRUD. |
| `--policy` | — | تولید Policy. |
| `--swagger` | `-sg` | تولید مستندات PHPDoc/OpenAPI ماژول؛ اگر تنها گزینه باشد فقط فایل Swagger تولید می‌شود. |
| `--all` | `-a` | تولید تمام لایه‌ها. |
| `--full` | `-f` | معادل `--all`. |
| `--from-migration=...` | `-fm` | استخراج metadata از migration. |
| `--fields=...` | — | تعریف schema داخل فرمان. |
| `--force` | — | بازنویسی فایل‌های موجود. |
| `--no-controller` | `-nc` | عدم تولید Controller. |
| `--no-resource` | `-nr` | عدم تولید Resource. |
| `--no-dto` | `-nd` | عدم تولید DTO. |
| `--no-test` | `-nt` | عدم تولید تست. |
| `--no-provider` | `-np` | عدم تولید و ثبت Provider. |
| `--no-actions` | — | عدم تولید Actionها. |
| `--no-policy` | — | عدم تولید Policy. |
| `--no-swagger` | — | عدم تولید مستندات Swagger. |

توجه: `--api` نام کوتاه ندارد. `-a` مربوط به `--all`، گزینه `-f` مربوط به `--full` و `--force` بدون نام کوتاه است.

نمونه‌های رایج:

```bash
# ماژول API با validation و Action
php artisan make:module Order --api

# Controller وب در مسیر دلخواه
php artisan make:module Invoice --controller=Admin/Accounting --no-actions

# تمام لایه‌ها
php artisan make:module Customer --all

# فقط فایل مستندات ماژول موجود
php artisan make:module Product --swagger

# Policy بدون Swagger
php artisan make:module Product --policy --no-swagger
```

<a id="تعریف-schema"></a>

### تعریف Schema

اطلاعات فیلدها به ترتیب از این منابع دریافت و ترکیب می‌شود:

1. گزینه `--fields`.
2. migration معرفی‌شده با `--from-migration`.
3. اطلاعات runtime مدل و ستون‌های دیتابیس.
4. مقادیر `$fillable` و castهای مدل.

فرمت هر فیلد:

```text
name:type:modifier:modifier
```

فیلدها با کاما جدا می‌شوند؛ کامای داخل پرانتز مثل `decimal(10,2)` بخشی از type است.

typeهای رایج:

- `string`، `char`، `varchar`
- `text`، `mediumText`، `longText`
- `integer`، `bigInteger`، `foreignId`
- `decimal`، `numeric`، `float`، `double`
- `boolean`
- `date`، `datetime`، `timestamp`
- `json`، `array`
- `uuid`، `email`، `url`

modifierها:

- `nullable`، `null` یا `optional`
- `required`، `notnull` یا `not-null`
- `unique` یا `uniq`
- `fk=table.column`
- `foreign=table.column`
- `references=table.column`

```bash
php artisan make:module Subscription --api \
  --fields="tenant_id:integer:fk=tenants.id,plan:string,status:string,expires_at:datetime:nullable"

php artisan make:module Product --api \
  --fields="sku:string:unique,price:decimal(12,2),metadata:json:nullable"

php artisan make:module Product --api \
  --from-migration=create_products_table
```

نام تکراری، keyword رزروشده PHP و identifier نامعتبر باعث توقف generation می‌شود.

<a id="فایلها-و-لایههای-تولیدی"></a>

### فایل‌ها و لایه‌های تولیدی

با توجه به گزینه‌ها و config، ساختاری مشابه زیر تولید می‌شود:

```text
app/
├── Actions/Product/
├── DTOs/ProductDTO.php
├── Docs/ProductDoc.php
├── Http/Controllers/Api/V1/ProductController.php
├── Http/Requests/Product/
├── Http/Resources/ProductResource.php
├── Policies/ProductPolicy.php
├── Providers/ProductServiceProvider.php
├── Repositories/Contracts/ProductRepositoryInterface.php
├── Repositories/Eloquent/ProductRepository.php
├── Services/Contracts/ProductServiceInterface.php
└── Services/ProductService.php

tests/Feature/ProductTest.php
```

- **Repository:** دسترسی به داده و queryها.
- **Service:** منطق application و business.
- **DTO:** انتقال داده type-safe از Request.
- **Action:** عملیات کوچک و مستقل CRUD همراه logging خطا.
- **FormRequest:** validation تولیدشده از schema.
- **Resource:** تبدیل خروجی API، تاریخ، boolean و relationها.
- **Controller:** هماهنگ‌کردن لایه‌های API یا Web.
- **Provider:** binding رابط‌ها به کلاس‌های concrete.
- **Policy:** اسکلت authorization.
- **Feature Test:** تست اولیه CRUD.
- **Swagger Doc:** annotationهای OpenAPI مربوط به ماژول.

فایل موجود بدون `--force` بازنویسی نمی‌شود. اگر یک generator شکست بخورد، فرمان وضعیت failure برمی‌گرداند.

<a id="تنظیمات-فارسی"></a>

### تنظیمات فارسی

فایل اصلی تنظیمات پس از publish برابر `config/module-generator.php` است.

```php
'base_namespace' => 'App',

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

'tests' => [
    'feature' => 'tests/Feature',
],
```

namespace فایل‌های تولیدی با pathها هماهنگ می‌شود. برای مثال با تغییر `dto` به `Data`، namespace و importهای DTO نیز به `App\Data` تغییر می‌کنند.

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
    'controller_type' => 'api',
],
```

چون مقدار پیش‌فرض `controller_type` برابر `api` است، اجرای ساده فرمان نیز Request و Action را فعال می‌کند. برای پروژه‌های وب مقدار آن را `web` قرار دهید.

کانال log مربوط به Actionهای تولیدی:

```env
MODULE_GENERATOR_LOG_CHANNEL=stack
```

<a id="apiهای-زمان-اجرا"></a>

### APIهای زمان اجرا

#### Repository

Repositoryهای تولیدشده از `BaseRepository` ارث‌بری می‌کنند و این عملیات را دارند:

- `getAll()`، `find($id)`، `store($data)`، `update($id, $data)` و `delete($id)`
- `findDynamic(...)` برای دریافت اولین نتیجه
- `getByDynamic(...)` برای دریافت collection
- `pushCriteria()`، `popCriteria()` و `skipCriteria()`

فیلترهای پویا شامل `where`، `with`، `whereNot`، `whereIn`، `whereNotIn`، between، null، نسخه‌های `orWhere` و عبارت‌های raw هستند.

```php
$products = $productRepository->getByDynamic(
    where: [['status', '=', 'active']],
    with: ['category'],
    whereIn: ['category_id', [1, 2, 3]],
    whereBetween: ['price', [100, 500]],
);

$product = $productRepository->findDynamic(
    where: [
        ['sku', '=', 'SKU-100'],
        ['is_active', '=', true],
    ],
);
```

#### Criteria

Criteria باید متد `apply(Builder $query): Builder` داشته باشد:

```php
namespace App\Repositories\Criteria;

use Illuminate\Database\Eloquent\Builder;

final class ActiveProductsCriteria
{
    public function apply(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
```

```php
$products = $productRepository
    ->pushCriteria(new ActiveProductsCriteria())
    ->getAll();

$productRepository->popCriteria(ActiveProductsCriteria::class);
$all = $productRepository->skipCriteria()->getAll();
```

#### Service و DTO

Service عملیات `index`، `show`، `store`، `update`، `destroy` و queryهای پویا را به Repository متصل می‌کند. همچنین متدهای Repository از طریق `__call()` قابل forward شدن هستند.

در حالت فعال بودن DTO، متدهای store و update یک DTO دریافت می‌کنند:

```php
use App\DTOs\ProductDTO;
use App\Services\ProductService;

$dto = ProductDTO::fromRequest($request);
$product = $productService->store($dto);

$dto->name;
$payload = $dto->toArray(); // مقادیر null حذف می‌شوند.
```

نوع propertyهای DTO از cast/schema استخراج می‌شود: integer به `?int`، boolean به `?bool`، numeric به `?float`، JSON/array به `?array` و سایر موارد به `?string`.

#### Action

Actionها invokable هستند و متد `execute()` نیز دارند. exception ثبت می‌شود و دوباره throw خواهد شد.

```php
use App\Actions\Product\CreateProductAction;
use App\DTOs\ProductDTO;

public function store(Request $request, CreateProductAction $createProduct)
{
    $product = $createProduct(ProductDTO::fromRequest($request));

    return response()->json($product, 201);
}
```

Actionهای list، list-with-relations، show، create، update و delete تولید می‌شوند.

#### Resource و Relation

Resource تولیدشده:

- شناسه مدل را همیشه اضافه می‌کند.
- تاریخ‌ها را با `ApiResponseHelper::formatDates()` تبدیل می‌کند.
- فیلدهای boolean/status را با `getStatus()` نمایش می‌دهد.
- برای `hasMany` و `belongsToMany` از `RelatedResource::collection()` استفاده می‌کند.
- برای relation تکی یک Resource ایجاد می‌کند.

```php
$product = $productService->getByDynamic(
    where: [['id', '=', $id]],
    with: ['category', 'tags'],
)->first();

return new ProductResource($product);
```

#### ApiResponseHelper

```php
use App\Helpers\ApiResponseHelper;

return ApiResponseHelper::successResponse($product, 'محصول ایجاد شد', 201);
return ApiResponseHelper::errorResponse('اعتبارسنجی ناموفق بود', 422, $errors);
return ApiResponseHelper::unauthorized();
return ApiResponseHelper::forbidden();
return ApiResponseHelper::notFound('محصول پیدا نشد');
```

```php
ApiResponseHelper::formatDates(now());
// date, time, fa_date, iso

ApiResponseHelper::getStatus(true);
// ['name' => 'active', 'fa_name' => 'فعال', 'code' => 1]
```

متد `getCabinType()` یک mapping نمونه و domain-specific در helper منتشرشده است و می‌توان آن را حذف یا شخصی‌سازی کرد.

#### Enum Helper

برای enumهای backed می‌توان از `EnumHelperTrait` استفاده کرد:

```php
namespace App\Enums;

use Efati\ModuleGenerator\Enums\Concerns\EnumHelperTrait;

enum OrderStatus: string
{
    use EnumHelperTrait;

    case Pending = 'pending';
    case Paid = 'paid';

    public function faName(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Paid => 'پرداخت‌شده',
        };
    }
}
```

```php
OrderStatus::toList();
OrderStatus::toMap();
OrderStatus::findByValue('paid');
OrderStatus::findByName('Paid');
```

هر خروجی شامل `name`، `fa_name` و `code` است.

#### Policy، Provider و تست تولیدی

- Policy شامل متدهای استاندارد authorization مدل است.
- Provider رابط Repository و Service را bind کرده و در `bootstrap/providers.php` یا `config/app.php` ثبت می‌شود.
- تست Feature یک route نوع `apiResource` ثبت می‌کند، payload را از factory/schema می‌سازد و عملیات CRUD را پوشش می‌دهد. داده‌های domain-specific باید پس از generation بازبینی شوند.

<a id="swagger-و-openapi"></a>

### Swagger و OpenAPI

پکیج دو مسیر مستندسازی دارد:

1. فرمان `swagger:generate` که از routeهای Laravel فایل OpenAPI JSON می‌سازد.
2. گزینه `make:module --swagger` که فایل PHPDoc مربوط به ماژول را تولید می‌کند.

workflow اصلی بدون `l5-swagger` و `swagger-php` کار می‌کند:

```bash
php artisan swagger:init
php artisan swagger:generate
php artisan swagger:ui
```

آدرس پیش‌فرض سرور مستقل `http://localhost:8000` است.

```bash
# تولید مجدد spec قبل از اجرا
php artisan swagger:ui --refresh

# تنظیم host و port
php artisan swagger:ui --host=localhost --port=8080

# تنظیم metadata خروجی
php artisan swagger:generate --title="Store API" --version=2.0.0
php artisan swagger:generate --host=https://api.example.com
php artisan swagger:generate --output=storage/swagger-ui/internal.json
```

تنظیم UI:

```bash
php artisan swagger:config --show
php artisan swagger:config --theme=dark --primary-color=#8b5cf6
php artisan swagger:config --title="Store API"
php artisan swagger:config --export-env
php artisan swagger:config --reset
php artisan swagger:init --force
```

تم‌های موجود: `vanilla`، `tailwind` و `dark`.

مهم‌ترین متغیرهای env:

```env
SWAGGER_THEME=vanilla
SWAGGER_COLOR_PRIMARY=#3b82f6
SWAGGER_COLOR_SECONDARY=#06b6d4
SWAGGER_UI_TITLE="API Documentation"
SWAGGER_UI_DESCRIPTION="REST API Documentation"
SWAGGER_SERVER_HOST=localhost
SWAGGER_SERVER_PORT=8000
SWAGGER_SPEC_PATH=storage/swagger-ui
SWAGGER_SPEC_FILENAME=swagger.json
SWAGGER_SECURE_SPEC=false
SWAGGER_PERSIST_AUTH=true
```

برای routeهای داخل خود Laravel:

```php
use Efati\ModuleGenerator\Http\Controllers\SwaggerUIController;
use Illuminate\Support\Facades\Route;

Route::get('/api/docs', [SwaggerUIController::class, 'index']);
Route::get('/api/swagger.json', [SwaggerUIController::class, 'spec']);
```

یا registrar قابل استفاده مجدد:

```php
namespace App\Support;

use Efati\ModuleGenerator\Traits\RegistersSwaggerRoutes;

final class SwaggerRoutes
{
    use RegistersSwaggerRoutes;
}
```

```php
SwaggerRoutes::registerSwaggerRoutes();
```

این فراخوانی routeهای `/docs` و `/docs/swagger.json` را در context فعلی route ثبت می‌کند. برای prefix یا middleware دلخواه، آن را داخل route group فراخوانی کنید.

با `SWAGGER_SECURE_SPEC=true` endpoint فایل JSON guardهای تعریف‌شده authentication را بررسی می‌کند.

فرمان قدیمی `make:swagger` برای backward compatibility باقی مانده است، اما برای UI داخلی استفاده از `swagger:generate` پیشنهاد می‌شود:

```bash
php artisan make:swagger --force
php artisan make:swagger --path=api/v1
php artisan make:swagger --controller=App\\Http\\Controllers\\Api
php artisan make:swagger --output=app/Docs
```

<a id="تاریخ-جلالی"></a>

### تاریخ جلالی

کلاس `Goli` تبدیل جلالی/میلادی را روی Carbon فراهم می‌کند. کلاس `Verta` از `Goli` ارث‌بری کرده و aliasهای نسخه قدیمی را حفظ می‌کند.

```php
use Efati\ModuleGenerator\Support\Goli;
use Efati\ModuleGenerator\Support\Verta;

$date = Goli::now('Asia/Tehran');
$date->toGoliDateString();
$date->toGoliDateTimeString();
$date->format('Y/m/d H:i', true);
$date->diffForHumans(persianDigits: true);

$parsed = Goli::parseGoli('1403/01/01 08:30:00', 'Asia/Tehran');
$carbon = $parsed->toCarbon();

[$gy, $gm, $gd] = Goli::goliToGregorian(1403, 1, 1);
[$jy, $jm, $jd] = Goli::gregorianToGoli(2024, 3, 20);

$legacy = Verta::now();
$legacy->toJalaliDateString();
Verta::jalaliToGregorian(1403, 1, 1);
```

helperها:

```php
$date = goli(now(), 'Asia/Tehran');
$same = goli_date(now());
```

APIهای مهم:

- `now()`، `parse()`، `parseGoli()`، `instance()`، `create()` و `fromTimestamp()`
- `setGoliDate()`، `setGoliDateTime()`، `addDays()` و `subDays()`
- `toGoliDateString()`، `toGoliDateTimeString()`، `toGoliArray()`
- `format()`، `formatGregorian()`، `diffForHumans()` و `toCarbon()`
- `goliToGregorian()`، `gregorianToGoli()` و `isLeapGoliYear()`
- `persianNumbers()` و `latinNumbers()`

تاریخ میلادی نامعتبر، طول نامعتبر ماه جلالی، روز ۳۰ اسفند در سال غیرکبیسه و زمان نامعتبر exception ایجاد می‌کنند.

استفاده از cast:

```php
use Efati\ModuleGenerator\Casts\GoliDateCast;

protected function casts(): array
{
    return [
        'published_at' => GoliDateCast::class,
    ];
}
```

استفاده از trait برای چند فیلد:

```php
use Efati\ModuleGenerator\Support\HasGoliDates;

class Post extends Model
{
    use HasGoliDates;

    protected array $goliDates = ['published_at', 'expires_at'];
}
```

مقادیر خوانده‌شده نمونه `Goli` هستند و مقادیر ورودی با فرمت میلادی مدل ذخیره می‌شوند. در runtime نیز می‌توان نوشت:

```php
$post->addGoliDateCast('approved_at');
```

<a id="تست-و-رفع-اشکال"></a>

### تست و رفع اشکال

```bash
composer install
composer test
```

lint در Linux یا داخل container:

```bash
find src tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

در Windows:

```powershell
Get-ChildItem src,tests -Recurse -Filter *.php |
  ForEach-Object { & 'C:\php-8.5\php.exe' -l $_.FullName }
```

workflow PHP در GitHub Actions نسخه‌های پشتیبانی‌شده PHP/Laravel را نصب، lint و PHPUnit را اجرا می‌کند.

مشکلات رایج:

- **Model پیدا نمی‌شود:** مدل را بسازید یا از `--fields`/`--from-migration` استفاده کنید.
- **فایل موجود skip می‌شود:** در صورت اطمینان `--force` اضافه کنید.
- **Stub پیدا نمی‌شود:** stubها را با `--force` دوباره publish کرده، `php artisan optimize:clear` و `composer dump-autoload` اجرا کنید.
- **Provider ثبت نشده:** Provider را دستی به `bootstrap/providers.php` یا `config/app.php` اضافه کنید.
- **Swagger UI وجود ندارد:** `swagger:init --force`، سپس `swagger:generate` و `swagger:ui` اجرا کنید.
- **تست تولیدی شکست می‌خورد:** دیتابیس تست، migrationها، factoryها و داده‌های اجباری domain را بررسی کنید.
- **namespace سفارشی autoload نمی‌شود:** `composer dump-autoload` اجرا کنید و مطمئن شوید path زیر namespace برنامه قرار دارد.

<a id="ارتقا-مشارکت-و-امنیت"></a>

### ارتقا، مشارکت و امنیت

نسخه فعلی در شاخه `7.x` است. برای ارتقا:

```bash
composer update efati/laravel-scaffolder
composer dump-autoload
composer test
```

قبل از publish مجدد config یا stubها، تغییرات محلی خود را بررسی کنید. ابتدا generation را روی یک fixture یا ماژول نمونه آزمایش کنید و سپس از `--force` در پروژه اصلی استفاده کنید.

برای مشارکت:

1. یک branch محدود و مشخص ایجاد کنید.
2. الگوی فعلی PHP و generatorها را رعایت کنید.
3. برای تغییرات رفتاری تست اضافه کنید.
4. lint و `composer test` را اجرا کنید.
5. در Pull Request اثر تغییر روی خروجی تولیدی و compatibility را توضیح دهید.

برای گزارش باگ، نسخه PHP/Laravel، فرمان اجراشده، config مرتبط، خروجی مورد انتظار، خروجی واقعی و نمونه بازتولید را ارسال کنید.

آسیب‌پذیری امنیتی را در Issue عمومی منتشر نکنید؛ از بخش Security Reporting گیت‌هاب یا راه ارتباطی خصوصی maintainer استفاده کنید.

این پروژه تحت مجوز MIT منتشر شده است. فایل `LICENSE` را ببینید.
