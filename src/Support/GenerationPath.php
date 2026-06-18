<?php

namespace Efati\ModuleGenerator\Support;

class GenerationPath
{
    public static function normalize(string $path): string
    {
        return trim(str_replace(['\\', '//'], '/', $path), '/');
    }

    public static function namespace(string $baseNamespace, string $path): string
    {
        $relative = str_replace('/', '\\', self::normalize($path));

        return trim($baseNamespace . '\\' . $relative, '\\');
    }

    public static function fqcn(string $baseNamespace, string $path, string $class): string
    {
        return self::namespace($baseNamespace, $path) . '\\' . $class;
    }
}
