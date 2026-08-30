---
title: Feature Map
description: Overview of the current Laravel Scaffolder feature set
---

# Feature Map

Laravel Scaffolder generates configurable Laravel application layers around an existing model/schema. This page is a map of the current feature set; detailed behavior lives in the linked guides so there is one source of truth for each capability.

## Module Generation

The `make:module` command can generate:

- Repository implementation and contract
- Service implementation and contract
- DTO
- API or web controller
- Store/Update form requests
- API resource
- Action layer
- CRUD policy
- Module service provider and application registration
- Feature-test scaffolding
- Swagger/OpenAPI documentation

Use the [CLI reference](./reference.md) for the exact current flags and shortcuts.

## Schema Sources

The generator can work with:

- Inline field metadata through `--fields=`
- An existing migration through `--from-migration`
- Runtime/model metadata when available

It merges and normalizes metadata for generators that need validation, relationships, DTO properties, resources, tests, or documentation.

See [schema-aware generation](./features/schema-aware-generation.md).

> Laravel Scaffolder does not create the Eloquent model, migration, factory, or seeder. Create those separately when your application needs them.

## Repository and Service Layers

Repository and service layers are core outputs of a normal `make:module` run. Published base classes provide the shared behavior and can be customized in the consuming application.

See:

- [Criteria pattern](./features/criteria-pattern.md)
- [Configuration](./configuration.md)

## DTOs

DTO generation is enabled by default in the shipped configuration and can be disabled per command with `--no-dto`. When DTOs are disabled, generated services/actions/controllers can use array payloads instead.

See [DTO generation](./features/dto-generation.md).

## Form Requests and API Resources

Use `--requests` to generate Store/Update requests. API resources are enabled by default unless `--no-resource` is supplied.

Field metadata is used to shape validation and serialization output.

## Action Layer

Use `--actions` to generate the action layer. The current action set consists of a shared `BaseAction` plus module actions for:

- List
- Show
- Create
- Update
- Delete
- ListWithRelations

See [Action Layer](./features/action-layer.md).

## Policies

Use `--policy` for a generated CRUD policy, or `--no-policy` when a configured default should be suppressed.

See [Policy generation](./features/policy-generation.md).

## Feature Tests

Use `--tests` to force feature-test generation on, or `--no-test` to disable it. The output directory is configurable under `tests.feature`.

See [Test generation](./features/test-generation.md).

## OpenAPI and Swagger UI

There are two related workflows:

1. `make:module --swagger` for module-oriented documentation generation.
2. `swagger:generate` / `swagger:init` / `swagger:ui` for the route-based OpenAPI JSON and bundled standalone UI.

See:

- [Swagger generation](./features/swagger-generation.md)
- [Route-Based OpenAPI & Swagger UI](./route-based-swagger.md)

## Jalali Dates

The runtime API provides:

- `goli()` and `goli_date()` helpers
- `Goli`
- `GoliDateCast`
- `HasGoliDates`
- Explicit `ApiResponseHelper::formatDates()` support

The package does not register Carbon `toJalali()` / `fromJalali()` macros.

See [Jalali Date Support](./features/jalali-support.md) and the [Public PHP API](./api-reference.md).

## Custom Stubs

Publish templates when you want project-specific generated code:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator-stubs
```

Templates are published to:

```text
resources/stubs/module-generator/
```

## Configuration

Publish the package config/base classes with:

```bash
php artisan vendor:publish \
  --provider="Efati\ModuleGenerator\ModuleGeneratorServiceProvider" \
  --tag=module-generator
```

`config/module-generator.php` controls the root namespace, output paths, generation defaults, Swagger UI/spec settings, and logging channel.

See the [configuration guide](./configuration.md).

## Practical Recipes

For copyable commands, use:

- [Quickstart](./quickstart.md)
- [Usage examples](./usage-examples.md)
- [CLI reference](./reference.md)
