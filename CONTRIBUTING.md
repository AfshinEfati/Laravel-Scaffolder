# Contributing

Thanks for considering a contribution to Laravel Scaffolder.

## Development Setup

Clone the repository and install development dependencies:

```bash
composer install
```

Because this is a Composer library, `composer.lock` is intentionally not committed.

Run the package test suite:

```bash
composer test
```

Lint PHP sources against the minimum supported syntax level:

```bash
find src tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Documentation Site

Documentation lives in `docs-site/`.

```bash
cd docs-site
npm ci --legacy-peer-deps
npm run generate
```

English and Persian documentation are maintained under:

```text
docs-site/content/en/
docs-site/content/fa/
```

When changing public behavior, commands, configuration, or generated output, update the relevant documentation in both languages when practical.

## Pull Requests

Keep pull requests focused and explain:

- what behavior changes;
- why the change is needed;
- how generated output is affected;
- whether there is any compatibility or migration impact.

Behavioral changes should include tests. Generator changes should preferably be covered by an integration test that executes the relevant Artisan command or validates generated files.

## Compatibility

The CI matrix is the source of truth for currently tested PHP/Laravel combinations. Avoid narrowing compatibility without documenting the reason and updating package metadata, tests, and documentation together.

## Generated Files

Existing generated application files are intentionally preserved unless `--force` is supplied. Changes must not weaken this overwrite protection unintentionally.

## Reporting Bugs

Please include:

- PHP version;
- Laravel version;
- package version/commit;
- command used;
- relevant `config/module-generator.php` values;
- expected and actual behavior;
- a minimal reproduction when possible.

For security-sensitive reports, use a private maintainer contact or GitHub's private vulnerability reporting when available instead of opening a public issue.
