<?php

namespace Efati\ModuleGenerator\Generators;

use Efati\ModuleGenerator\Support\MigrationFieldParser;
use Efati\ModuleGenerator\Support\ModelInspector;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Efati\ModuleGenerator\Support\SchemaParser;
use Efati\ModuleGenerator\Support\GenerationPath;

class ResourceGenerator
{
    public static function generate(
        string $name,
        string $baseNamespace = 'App',
        bool $force = false,
        ?array $fields = null,
        array $migrationRelations = []
    ): array {

        $paths = config('module-generator.paths', []);
        $resourceRel = $paths['resource'] ?? ($paths['resources'] ?? 'Http/Resources');

        $resourcePath = app_path($resourceRel);
        File::ensureDirectoryExists($resourcePath);

        $className = "{$name}Resource";
        $filePath  = $resourcePath . "/{$className}.php";

        $modelFqcn  = "{$baseNamespace}\\Models\\{$name}";
        $helperFqcn = "{$baseNamespace}\\Helpers\\ApiResponseHelper";

        $fillable  = self::resolveFillable($modelFqcn, $fields);
        $casts     = self::resolveCasts($modelFqcn, $fields);
        $relations = self::resolveRelations($modelFqcn, $baseNamespace, $migrationRelations);


        $content = self::build($className, $baseNamespace, $helperFqcn, $fillable, $relations, $casts, $modelFqcn);

        return [$filePath => self::writeFile($filePath, $content, $force)];
    }

    private static function resolveFillable(string $modelFqcn, ?array $fields): array
    {
        if (is_array($fields) && !empty($fields)) {
            return MigrationFieldParser::buildFillableFromFields($fields);
        }

        return self::getFillable($modelFqcn);
    }

    private static function resolveCasts(string $modelFqcn, ?array $fields): array
    {
        if (is_array($fields) && !empty($fields)) {
            return MigrationFieldParser::buildCastsFromFields($fields);
        }

        return self::getModelCasts($modelFqcn);
    }

    private static function getFillable(string $modelFqcn): array
    {
        return ModelInspector::extractFillable($modelFqcn);
    }

    private static function getModelCasts(string $modelFqcn): array
    {
        if (!class_exists($modelFqcn)) {
            return [];
        }
        $m = new $modelFqcn();
        return method_exists($m, 'getCasts') ? $m->getCasts() : [];
    }

    private static function detectRelations(string $modelFqcn): array
    {
        if (!class_exists($modelFqcn)) {
            return [];
        }

        try {
            $m = new $modelFqcn();
        } catch (\Throwable $e) {
            return [];
        }

        $rels = [];

        $methods = get_class_methods($m);
        if (!is_array($methods)) {
            return [];
        }

        foreach ($methods as $method) {
            if (in_array($method, ['boot', 'booted', '__construct'], true)) {
                continue;
            }

            try {
                $reflection = new \ReflectionMethod($m, $method);

                // Skip static and private methods
                if ($reflection->isStatic() || $reflection->isPrivate()) {
                    continue;
                }

                // Skip methods that require parameters
                if ($reflection->getNumberOfRequiredParameters() > 0) {
                    continue;
                }

                $ret = $m->$method();
                if (is_object($ret) && method_exists($ret, 'getRelated')) {
                    $rels[$method] = [
                        'related' => get_class($ret->getRelated()),
                        'type' => lcfirst(class_basename($ret)),
                    ];
                }
            } catch (\Throwable $e) {
                // ignore relation that throws
                continue;
            }
        }

        return $rels;
    }

    private static function resolveRelations(string $modelFqcn, string $baseNamespace, array $migrationRelations): array
    {
        $relations = [];

        foreach ($migrationRelations as $key => $info) {
            if (!is_array($info)) {
                continue;
            }
            $name = $info['name'] ?? (is_string($key) ? $key : null);
            if (!$name) {
                continue;
            }
            $relatedModel = $info['related_model'] ?? Str::studly($name);
            if (is_string($relatedModel) && str_contains($relatedModel, '\\')) {
                $base = class_basename($relatedModel);
                $model = $relatedModel;
            } else {
                $base = (string) $relatedModel;
                $model = $baseNamespace . '\\Models\\' . $base;
            }
            $resourceRel = self::resourceRelativePath();
            $relations[$name] = [
                'model' => $model,
                'resource' => GenerationPath::fqcn($baseNamespace, $resourceRel, $base . 'Resource'),
                'type' => $info['type'] ?? 'belongsTo',
            ];
        }

        foreach (self::detectRelations($modelFqcn) as $rel => $relation) {
            $relatedFqcn = $relation['related'];
            $base = class_exists($relatedFqcn) ? class_basename($relatedFqcn) : Str::studly($rel);
            $relations[$rel] = [
                'model'    => $relatedFqcn,
                'resource' => GenerationPath::fqcn($baseNamespace, self::resourceRelativePath(), $base . 'Resource'),
                'type' => $relation['type'] ?? null,
            ];
        }

        return $relations;
    }

    private static function build(
        string $className,
        string $baseNamespace,
        string $helperFqcn,
        array $fillable,
        array $relations,
        array $casts,
        string $modelFqcn
    ): string {
        $ns = GenerationPath::namespace($baseNamespace, self::resourceRelativePath());
        $uses = [
            'Illuminate\\Http\\Resources\\Json\\JsonResource',
            $helperFqcn,
            $modelFqcn,
        ];

        $usesBlock = self::buildUses($uses);
        $modelBasename = \class_basename($modelFqcn);
        $mixinDoc = "/**\n * @mixin {$modelBasename}\n */";

        $body = [];

        $resourceFields = $fillable;
        if (!in_array('id', $resourceFields, true)) {
            array_unshift($resourceFields, 'id');
        }

        foreach ($resourceFields as $field) {
            $castType = self::normalizeCast($casts[$field] ?? null);
            if ($castType === 'datetime' || $castType === 'date' || Str::endsWith($field, ['_at'])) {
                $body[] = "            '{$field}' => ApiResponseHelper::formatDates(\$this->{$field}),";
            } elseif ($castType === 'boolean' || $castType === 'bool' || Str::startsWith($field, ['is_', 'has_'])) {
                $body[] = "            '{$field}' => ApiResponseHelper::getStatus((bool) \$this->{$field}),";
            } else {
                $body[] = "            '{$field}' => \$this->{$field},";
            }
        }

        foreach ($relations as $rel => $meta) {
            $resourceFqcn = $meta['resource'];
            $relationType = $meta['type'] ?? null;
            $resourceExpression = in_array($relationType, ['hasMany', 'belongsToMany', 'morphMany', 'morphToMany'], true)
                ? "\\{$resourceFqcn}::collection(\$this->whenLoaded('{$rel}'))"
                : "new \\{$resourceFqcn}(\$this->whenLoaded('{$rel}'))";
            $body[] = "            '{$rel}' => class_exists('{$resourceFqcn}')"
                . "\n                ? {$resourceExpression}"
                . "\n                : \$this->whenLoaded('{$rel}'),";
        }

        $bodyBlock = implode("\n", $body);

        if ($bodyBlock !== '') {
            $arrayBody = "        return [\n{$bodyBlock}\n        ];\n";
        } else {
            $arrayBody = "        return [];\n";
        }

        $classBody = "class {$className} extends JsonResource\n{\n    public function toArray(\$request): array\n    {\n{$arrayBody}    }\n}\n";

        $segments = [
            "<?php",
            "",
            "namespace {$ns};",
            "",
            $usesBlock !== '' ? $usesBlock : null,
            "",
            $mixinDoc,
            $classBody,
        ];

        return implode("\n", array_filter($segments, static fn ($segment) => $segment !== null));

    }

    private static function buildUses(array $uses): string
    {
        $uses = array_values(array_unique(array_filter($uses)));

        if (empty($uses)) {
            return '';
        }

        return 'use ' . implode(";\nuse ", $uses) . ';';
    }

    private static function writeFile(string $path, string $contents, bool $force): bool
    {
        if (!$force && File::exists($path)) {
            return false;
        }

        File::put($path, $contents);

        return true;
    }

    private static function normalizeCast(?string $cast): ?string
    {
        if ($cast === null) {
            return null;
        }

        $cast = strtolower($cast);
        if (str_contains($cast, ':')) {
            $cast = strstr($cast, ':', true);
        }

        return match ($cast) {
            'datetime', 'immutable_datetime', 'custom_datetime' => 'datetime',
            'date', 'immutable_date' => 'date',
            'bool', 'boolean' => 'boolean',
            default => $cast,
        };
    }

    private static function resourceRelativePath(): string
    {
        $paths = config('module-generator.paths', []);
        $resourceRel = $paths['resource'] ?? ($paths['resources'] ?? 'Http/Resources');

        return is_string($resourceRel) && $resourceRel !== '' ? $resourceRel : 'Http/Resources';
    }
}
