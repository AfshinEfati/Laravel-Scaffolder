<?php

namespace Efati\ModuleGenerator\Generators;

use Efati\ModuleGenerator\Support\MigrationFieldParser;
use Efati\ModuleGenerator\Support\ModelInspector;
use Efati\ModuleGenerator\Support\Stub;
use Efati\ModuleGenerator\Support\GenerationPath;
use Illuminate\Support\Facades\File;

class DTOGenerator
{
    public static function generate(string $name, string $baseNamespace = 'App', bool $force = false, ?array $fields = null): array
    {
        $paths = config('module-generator.paths', []);
        $dtoRel = $paths['dto'] ?? ($paths['dtos'] ?? 'DTOs');

        $dtoPath = app_path($dtoRel);
        File::ensureDirectoryExists($dtoPath);

        $className = "{$name}DTO";
        $filePath  = $dtoPath . "/{$className}.php";

        $modelFqcn = "{$baseNamespace}\\Models\\{$name}";
        $fillable = self::resolveFillable($modelFqcn, $fields);
        $fieldMetadata = self::resolveFieldMetadata($modelFqcn, $fields);

        $content = self::build(
            $className,
            GenerationPath::namespace($baseNamespace, $dtoRel),
            $fillable,
            $fieldMetadata
        );

        return [$filePath => self::writeFile($filePath, $content, $force)];
    }

    /**
     * Resolve field metadata from model or migrations
     */
    private static function resolveFieldMetadata(string $modelFqcn, ?array $fields): array
    {
        if (is_array($fields) && !empty($fields)) {
            $metadata = [];
            foreach ($fields as $key => $field) {
                if (is_array($field) && isset($field['name'])) {
                    $metadata[$field['name']] = $field;
                } elseif (is_string($key) && is_array($field)) {
                    $field['name'] = $key;
                    $metadata[$key] = $field;
                }
            }
            return $metadata;
        }

        // Try to extract from model casts
        if (class_exists($modelFqcn)) {
            try {
                $model = new $modelFqcn();
                if (method_exists($model, 'getCasts')) {
                    $metadata = [];
                    foreach ($model->getCasts() as $field => $cast) {
                        $metadata[$field] = ['cast' => $cast];
                    }

                    return $metadata;
                }
            } catch (\Throwable $e) {
                // Silently fail
            }
        }

        return [];
    }

    private static function resolveFillable(string $modelFqcn, ?array $fields): array
    {
        if (is_array($fields) && !empty($fields)) {
            return MigrationFieldParser::buildFillableFromFields($fields);
        }

        return self::getFillable($modelFqcn);
    }

    private static function getFillable(string $modelFqcn): array
    {
        return ModelInspector::extractFillable($modelFqcn);
    }

    private static function build(string $className, string $namespace, array $fillable, ?array $fieldMetadata = null): string
    {
        $constructorSignature = [];
        $fromRequestArguments = [];
        $toArrayBody = [];

        foreach ($fillable as $f) {
            // Infer PHP type from field metadata if available
            $phpType = self::inferPhpType($f, $fieldMetadata);
            $constructorSignature[] = "        public readonly {$phpType} \${$f} = null";
            $fromRequestArguments[] = "            {$f}: \$request->input('{$f}'),";
            $toArrayBody[] = "        if (\$this->{$f} !== null) { \$out['{$f}'] = \$this->{$f}; }";
        }

        return Stub::render('DTO/dto', [
            'namespace'             => $namespace,
            'class'                 => $className,
            'constructor_signature' => implode(",\n", $constructorSignature),
            'from_request_arguments' => implode("\n", $fromRequestArguments),
            'to_array_body'         => implode("\n", $toArrayBody),
        ]);
    }

    /**
     * Infer the PHP type for a field based on its name and metadata
     */
    private static function inferPhpType(string $fieldName, ?array $fieldMetadata = null): string
    {
        // If metadata is available, use it
        if (is_array($fieldMetadata) && is_array($fieldMetadata[$fieldName] ?? null)) {
            $type = $fieldMetadata[$fieldName]['type'] ?? $fieldMetadata[$fieldName]['cast'] ?? null;
            if ($type) {
                return self::mapDatabaseTypeToPHP($type);
            }
        }

        // Heuristic inference from field names
        if (str_ends_with($fieldName, '_id')) {
            return 'int|string|null';
        }
        if (str_contains($fieldName, 'email')) {
            return '?string';
        }
        if (str_contains($fieldName, 'count') || str_contains($fieldName, 'quantity')) {
            return 'int|string|null';
        }
        if (str_contains($fieldName, 'price') || str_contains($fieldName, 'amount') || str_contains($fieldName, 'rate')) {
            return 'float|int|string|null';
        }
        if (str_contains($fieldName, 'is_') || str_contains($fieldName, 'has_') || str_contains($fieldName, 'active')) {
            return 'bool|int|string|null';
        }
        if (str_contains($fieldName, 'date') || str_ends_with($fieldName, '_at')) {
            return 'string|\Carbon\Carbon|null';
        }
        if (str_contains($fieldName, 'json') || str_contains($fieldName, 'data')) {
            return 'array|string|null';
        }

        // Default to mixed if unable to infer
        return 'mixed';
    }

    /**
     * Map database column type to PHP type
     */
    private static function mapDatabaseTypeToPHP(string $dbType): string
    {
        $dbType = strtolower(trim($dbType));

        return match ($dbType) {
            'string', 'char', 'varchar', 'text', 'mediumtext', 'longtext' => '?string',
            'int', 'integer', 'bigint', 'smallint', 'tinyint', 'mediumint', 'increments', 'bigincrements' => 'int|string|null',
            'float', 'double', 'decimal', 'numeric' => 'float|int|string|null',
            'bool', 'boolean' => 'bool|int|string|null',
            'date', 'datetime', 'timestamp', 'datetimetz', 'timestamptz' => 'string|\Carbon\Carbon|null',
            'json', 'jsonb', 'array' => 'array|string|null',
            'uuid', 'email', 'url' => '?string',
            default => 'mixed',
        };
    }

    private static function writeFile(string $path, string $content, bool $force): bool
    {
        if (!$force && File::exists($path)) {
            return false;
        }

        File::put($path, $content);

        return true;
    }
}
