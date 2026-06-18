<?php

namespace Efati\ModuleGenerator\Generators;

use Efati\ModuleGenerator\Support\Stub;
use Efati\ModuleGenerator\Support\GenerationPath;
use Illuminate\Support\Facades\File;

class ProviderGenerator
{
    public static function generateAndRegister(string $name, string $baseNamespace = 'App', bool $force = false): array
    {
        $paths = config('module-generator.paths', []);
        $providerRel = $paths['provider'] ?? ($paths['providers'] ?? 'Providers');

        $providerPath = app_path($providerRel);
        File::ensureDirectoryExists($providerPath);

        $repoPaths = $paths['repository'] ?? ($paths['repositories'] ?? []);
        $repoEloquentRel = is_array($repoPaths) ? ($repoPaths['eloquent'] ?? 'Repositories/Eloquent') : 'Repositories/Eloquent';
        $repoContractsRel = is_array($repoPaths) ? ($repoPaths['contracts'] ?? 'Repositories/Contracts') : 'Repositories/Contracts';
        $servicePaths = $paths['service'] ?? ($paths['services'] ?? []);
        $serviceRel = is_array($servicePaths) ? ($servicePaths['concretes'] ?? 'Services') : 'Services';
        $serviceContractsRel = is_array($servicePaths) ? ($servicePaths['contracts'] ?? 'Services/Contracts') : 'Services/Contracts';

        $repoNs = GenerationPath::fqcn($baseNamespace, $repoEloquentRel, "{$name}Repository");
        $repoIf = GenerationPath::fqcn($baseNamespace, $repoContractsRel, "{$name}RepositoryInterface");
        $serviceNs = GenerationPath::fqcn($baseNamespace, $serviceRel, "{$name}Service");
        $serviceIf = GenerationPath::fqcn($baseNamespace, $serviceContractsRel, "{$name}ServiceInterface");
        $provNs = GenerationPath::namespace($baseNamespace, $providerRel);
        $class     = "{$name}ServiceProvider";

        $content = Stub::render('Provider/provider', [
            'namespace'           => $provNs,
            'class'               => $class,
            'repository_interface'=> $repoIf,
            'repository_concrete' => $repoNs,
            'service_interface'   => $serviceIf,
            'service_concrete'    => $serviceNs,
        ]);
        $providerFile = $providerPath . "/{$class}.php";
        $results = [
            $providerFile => self::writeFile($providerFile, $content, $force),
        ];

        $results = array_merge($results, self::registerProvider("{$provNs}\\{$class}"));

        return $results;
    }

    private static function registerProvider(string $fqcn): array
    {
        $results = [];
        $bootstrapProviders = base_path('bootstrap/providers.php');

        if (File::exists($bootstrapProviders)) {
            try {
                $contents = File::get($bootstrapProviders);
                if (!str_contains($contents, $fqcn . '::class')) {
                    $newContents = self::insertIntoReturnedArray($contents, $fqcn);
                    $results[$bootstrapProviders] = $newContents !== null;
                    if ($newContents !== null) {
                        File::put($bootstrapProviders, $newContents);
                    }
                } else {
                    $results[$bootstrapProviders] = false;
                }
            } catch (\Throwable $e) {
                $results[$bootstrapProviders] = false;
            }
            return $results;
        }

        $configApp = config_path('app.php');
        if (File::exists($configApp)) {
            try {
                $contents = File::get($configApp);
                if (!str_contains($contents, $fqcn . '::class')) {
                    $pattern = '/(\'providers\'\s*=>\s*\[)(.*?)(\n\s*\],)/s';
                    if (preg_match($pattern, $contents)) {
                        $newContents = preg_replace_callback($pattern, static function (array $matches) use ($fqcn): string {
                            $body = rtrim($matches[2]);
                            if ($body !== '' && !str_ends_with($body, ',')) {
                                $body .= ',';
                            }
                            $body .= "\n        {$fqcn}::class,";

                            return $matches[1] . $body . $matches[3];
                        }, $contents, 1);

                        if (is_string($newContents) && $newContents !== $contents) {
                            File::put($configApp, $newContents);
                            $results[$configApp] = true;
                        } else {
                            $results[$configApp] = false;
                        }
                    } else {
                        $results[$configApp] = false;
                    }
                } else {
                    $results[$configApp] = false;
                }
            } catch (\Throwable $e) {
                $results[$configApp] = false;
            }
        }

        return $results;
    }

    private static function insertIntoReturnedArray(string $contents, string $fqcn): ?string
    {
        $pattern = '/(return\s*\[)(.*?)(\]\s*;)/s';

        $updated = preg_replace_callback($pattern, static function (array $matches) use ($fqcn): string {
            $body = rtrim($matches[2]);
            if ($body !== '' && !str_ends_with($body, ',')) {
                $body .= ',';
            }
            $body .= "\n    {$fqcn}::class,\n";

            return $matches[1] . $body . $matches[3];
        }, $contents, 1);

        return is_string($updated) && $updated !== $contents ? $updated : null;
    }

    private static function writeFile(string $path, string $contents, bool $force): bool
    {
        if (!$force && File::exists($path)) {
            return false;
        }

        File::put($path, $contents);

        return true;
    }
}
