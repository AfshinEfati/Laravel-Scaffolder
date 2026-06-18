<?php

namespace Efati\ModuleGenerator\Support;

use Illuminate\Support\Facades\File;

class SwaggerConfigManager
{
    /**
     * Get Swagger configuration
     */
    public static function getConfig(): array
    {
        return config('module-generator.swagger', []);
    }

    /**
     * Get theme name
     */
    public static function getTheme(): string
    {
        return config('module-generator.swagger.theme', 'vanilla');
    }

    /**
     * Get color configuration
     */
    public static function getColors(): array
    {
        return config('module-generator.swagger.colors', [
            'primary' => '#3b82f6',
            'primary_dark' => '#1e40af',
            'primary_light' => '#eff6ff',
            'secondary' => '#06b6d4',
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'danger' => '#ef4444',
            'dark' => '#1f2937',
            'light' => '#f9fafb',
            'border' => '#e5e7eb',
            'text' => '#374151',
            'text_light' => '#6b7280',
        ]);
    }

    /**
     * Get fonts configuration
     */
    public static function getFonts(): array
    {
        return config('module-generator.swagger.fonts', [
            'family' => 'system-ui, -apple-system, sans-serif',
            'mono' => '"Fira Code", "Courier New", monospace',
        ]);
    }

    /**
     * Get dark mode configuration
     */
    public static function getDarkModeConfig(): array
    {
        return config('module-generator.swagger.dark_mode', [
            'enabled' => true,
            'default' => 'auto',
            'persist' => true,
        ]);
    }

    /**
     * Generate CSS variables from colors config
     */
    public static function generateCSSVariables(): string
    {
        $colors = self::getColors();
        $css = ":root {\n";

        foreach ($colors as $name => $value) {
            $varName = str_replace('_', '-', $name);
            $css .= "    --{$varName}: {$value};\n";
        }

        // Add fonts
        $fonts = self::getFonts();
        $css .= "    --font-family: {$fonts['family']};\n";
        $css .= "    --font-mono: {$fonts['mono']};\n";

        $css .= "}\n";

        return $css;
    }

    /**
     * Generate Tailwind color config
     */
    public static function generateTailwindColors(): array
    {
        $colors = self::getColors();
        $primary = $colors['primary'] ?? '#3b82f6';

        // Create primary color palette
        $primaryPalette = self::generateColorPalette($primary);

        return [
            'primary' => $primaryPalette,
        ];
    }

    /**
     * Generate color palette from a hex color
     */
    public static function generateColorPalette(string $hex): array
    {
        $normalized = ltrim(trim($hex), '#');
        if (strlen($normalized) === 3) {
            $normalized = implode('', array_map(
                static fn (string $digit): string => $digit . $digit,
                str_split($normalized)
            ));
        }

        if (!preg_match('/^[0-9a-f]{6}$/i', $normalized)) {
            throw new \InvalidArgumentException('Color must be a valid 3 or 6 digit hexadecimal value.');
        }

        $rgb = [
            hexdec(substr($normalized, 0, 2)),
            hexdec(substr($normalized, 2, 2)),
            hexdec(substr($normalized, 4, 2)),
        ];
        $mixes = [
            50 => [255, 0.92],
            100 => [255, 0.82],
            200 => [255, 0.65],
            300 => [255, 0.45],
            400 => [255, 0.22],
            500 => [null, 0.0],
            600 => [0, 0.12],
            700 => [0, 0.25],
            800 => [0, 0.38],
            900 => [0, 0.5],
        ];

        $palette = [];
        foreach ($mixes as $shade => [$target, $amount]) {
            $channels = array_map(
                static fn (int $channel): int => $target === null
                    ? $channel
                    : (int) round($channel + (($target - $channel) * $amount)),
                $rgb
            );
            $palette[$shade] = sprintf('#%02x%02x%02x', ...$channels);
        }

        return $palette;
    }

    /**
     * Inject colors into HTML file
     */
    public static function injectColorsIntoHtml(string $htmlContent): string
    {
        $theme = self::getTheme();

        if ($theme === 'vanilla' || $theme === 'tailwind') {
            $colors = self::getColors();
            $cssVars = self::generateCSSVariables();

            // Find the style tag and inject CSS variables
            if (preg_match('/<style[^>]*>/', $htmlContent, $matches)) {
                $styleTag = $matches[0];
                $injection = $styleTag . "\n" . $cssVars;
                $htmlContent = str_replace($styleTag, $injection, $htmlContent);
            }
        }

        if ($theme === 'dark') {
            $darkConfig = self::getDarkModeConfig();
            $defaultMode = $darkConfig['default'] ?? 'auto';
            $persist = $darkConfig['persist'] ?? true;

            // Inject dark mode settings
            $htmlContent = str_replace(
                "darkMode: 'auto',",
                "darkMode: '{$defaultMode}',\npersist: " . ($persist ? 'true' : 'false') . ",",
                $htmlContent
            );
        }

        return $htmlContent;
    }

    /**
     * Get UI display configuration
     */
    public static function getDisplayConfig(): array
    {
        return config('module-generator.swagger.display', [
            'title' => 'API Documentation',
            'description' => 'REST API Documentation',
            'show_models' => true,
            'show_examples' => true,
            'persist_auth' => true,
        ]);
    }

    /**
     * Get server configuration
     */
    public static function getServerConfig(): array
    {
        return config('module-generator.swagger.server', [
            'port' => 8000,
            'host' => 'localhost',
        ]);
    }

    /**
     * Get spec configuration
     */
    public static function getSpecConfig(): array
    {
        return config('module-generator.swagger.spec', [
            'path' => 'storage/swagger-ui',
            'filename' => 'swagger.json',
        ]);
    }

    public static function specPath(): string
    {
        $config = self::getSpecConfig();
        $directory = trim((string) ($config['path'] ?? 'storage/swagger-ui'));
        $filename = basename((string) ($config['filename'] ?? 'swagger.json'));
        $directory = $directory !== '' ? $directory : 'storage/swagger-ui';

        $base = str_starts_with($directory, DIRECTORY_SEPARATOR)
            ? $directory
            : base_path($directory);

        return rtrim($base, '/\\') . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Apply colors to UI file
     */
    public static function applyColorsToUI(): bool
    {
        $storagePath = storage_path('swagger-ui');
        $indexPath = $storagePath . '/index.html';

        if (!File::exists($indexPath)) {
            return false;
        }

        $htmlContent = File::get($indexPath);
        $updatedContent = self::injectColorsIntoHtml($htmlContent);

        if ($updatedContent !== $htmlContent) {
            File::put($indexPath, $updatedContent);
            return true;
        }

        return false;
    }

    /**
     * Export configuration as environment variables
     */
    public static function exportAsEnv(): string
    {
        $colors = self::getColors();
        $fonts = self::getFonts();
        $darkMode = self::getDarkModeConfig();
        $display = self::getDisplayConfig();
        $server = self::getServerConfig();
        $spec = self::getSpecConfig();

        $env = "# Swagger UI Configuration\n";
        $env .= "SWAGGER_THEME=" . self::getTheme() . "\n";
        $env .= "\n# Colors\n";

        foreach ($colors as $name => $value) {
            $env .= "SWAGGER_COLOR_" . strtoupper($name) . "={$value}\n";
        }

        $env .= "\n# Fonts\n";
        $env .= "SWAGGER_FONT_FAMILY=" . $fonts['family'] . "\n";
        $env .= "SWAGGER_FONT_MONO=" . $fonts['mono'] . "\n";

        $env .= "\n# Dark Mode\n";
        $env .= "SWAGGER_DARK_MODE_ENABLED=" . ($darkMode['enabled'] ? 'true' : 'false') . "\n";
        $env .= "SWAGGER_DARK_MODE_DEFAULT=" . $darkMode['default'] . "\n";
        $env .= "SWAGGER_DARK_MODE_PERSIST=" . ($darkMode['persist'] ? 'true' : 'false') . "\n";

        $env .= "\n# Display\n";
        $env .= "SWAGGER_UI_TITLE=" . $display['title'] . "\n";
        $env .= "SWAGGER_UI_DESCRIPTION=" . $display['description'] . "\n";
        $env .= "SWAGGER_PERSIST_AUTH=" . ($display['persist_auth'] ? 'true' : 'false') . "\n";

        $env .= "\n# Server\n";
        $env .= "SWAGGER_SERVER_PORT=" . $server['port'] . "\n";
        $env .= "SWAGGER_SERVER_HOST=" . $server['host'] . "\n";

        $env .= "\n# Spec Output\n";
        $env .= "SWAGGER_SPEC_PATH=" . $spec['path'] . "\n";
        $env .= "SWAGGER_SPEC_FILENAME=" . $spec['filename'] . "\n";

        return $env;
    }
}
