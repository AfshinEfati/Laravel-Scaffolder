# Changelog

[🇮🇷 فارسی](../fa/changelog.md){ .language-switcher }

This page tracks changes that are currently on `main`. Published version numbers and package metadata are available from Packagist and GitHub tags; historical release notes are not reconstructed here when the repository does not contain authoritative notes for a tag.

## Unreleased (`main`)

### Compatibility and CI

- Expanded declared/tested compatibility through Laravel 13 and Orchestra Testbench 11.
- Added a PHP 8.1 syntax-lint job for the minimum supported PHP version.
- Added an 8-job Laravel/PHP compatibility matrix covering Laravel 10–13 and PHP 8.1–8.5 combinations.
- Modernized GitHub Actions used by the package and documentation workflows.
- Documentation builds now use `npm ci` and deploy through the current GitHub Pages actions.

### Package behavior

- Package assets are no longer copied into consuming applications merely because Artisan boots. Configuration, base classes, helpers, and custom stubs are now published only through explicit `vendor:publish` commands.
- Fixed `Goli::diffForHumans()` with newer Carbon versions where fractional `diffIn*()` values could select the wrong unit and produce output such as `0 years`.
- Kept generated files safe by preserving the existing skip-without-`--force` behavior and covering it with integration tests.
- Missing models now have integration coverage to ensure generation fails early when no model, inline schema, or migration metadata is available.

### Tests

- Added Orchestra Testbench integration coverage for package command registration and publish groups.
- Added an integration test that executes the real `make:module` Artisan command and verifies generated repository/service files.
- Added coverage for CLI shortcut contracts so `-a` remains `--all`, `-f` remains `--full`, and `--api` / `--force` do not silently acquire conflicting shortcuts.
- Converted the former manual Goli date-cast script into PHPUnit coverage.
- Removed obsolete test bootstrap/stub artifacts.

### Repository and distribution

- Removed tracked `vendor/`, PHPUnit cache/debug artifacts, Composer lockfile, and generated Nuxt Content database artifacts from the package repository.
- Added `autoload-dev`, Composer package metadata cleanup, `.gitattributes` export exclusions, and `.editorconfig`.
- Kept development-only directories such as tests, docs, examples, and GitHub workflow files out of Composer source distributions via `export-ignore`.

### Documentation

- Rebuilt installation, quickstart, configuration, CLI reference, Swagger/OpenAPI, public PHP API, Jalali, usage examples, and feature-map pages against the current source code.
- Removed documentation for commands, facades, services, Carbon macros, factories, migrations, seeders, and web UI behavior that the current package does not provide.
- Replaced the obsolete Carbon-macro example with a working `examples/goli-date.php` example based on `Goli` and `goli()`.
- Clarified that Laravel Scaffolder generates application layers around an existing model/schema and does not create the Eloquent model or migration itself.

## Published Releases

Packagist currently lists the published `8.x` release line, including `v8.1.2`, `v8.1.1`, `v8.1.0`, and `v8.0.x` tags.

- Package registry: https://packagist.org/packages/efati/laravel-scaffolder
- Repository tags: https://github.com/AfshinEfati/Laravel-Scaffolder/tags

Before publishing the current `main` changes as a new version, review the diff from the latest published tag and create release notes from that exact comparison.
