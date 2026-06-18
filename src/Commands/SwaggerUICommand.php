<?php

namespace Efati\ModuleGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class SwaggerUICommand extends Command
{
    protected $signature = 'swagger:ui
                            {--port=8000 : The port to serve on}
                            {--host=localhost : The host to serve on}
                            {--refresh : Regenerate swagger.json before serving}';

    protected $description = 'Start a standalone Swagger UI server (no L5-Swagger dependency required)';

    public function handle(): int
    {
        $this->info('🚀 Starting Custom Swagger UI...');

        // Use config values if not overridden by options
        $port = $this->option('port') !== 8000
            ? $this->option('port')
            : config('module-generator.swagger.server.port', 8000);

        $host = $this->option('host') !== 'localhost'
            ? $this->option('host')
            : config('module-generator.swagger.server.host', 'localhost');

        $refresh = $this->option('refresh');

        if ($refresh && $this->call('swagger:generate') !== self::SUCCESS) {
            return self::FAILURE;
        }

        // Create a simple HTTP server that serves Swagger UI
        $uiPath = base_path('storage/swagger-ui');

        if (!File::exists($uiPath)) {
            $this->error('Swagger UI not initialized. Run: php artisan swagger:init');
            return 1;
        }

        // Validate host and port to prevent injection
        if (!is_string($host) || !$this->isValidHost($host)) {
            $this->error('Invalid host provided');
            return 1;
        }

        if (!$this->isValidPort($port)) {
            $this->error('Invalid port provided');
            return 1;
        }

        $port = (int) $port;
        $displayHost = $host === '::1' ? '[::1]' : $host;
        $url = "http://{$displayHost}:{$port}";

        $this->info('');
        $this->info('✨ Swagger UI is running at: ' . $this->formatOutput($url, 'fg=cyan'));
        $this->info('📊 API Documentation: ' . $this->formatOutput($url, 'fg=green'));
        $this->info('');

        $theme = config('module-generator.swagger.theme', 'vanilla');
        $this->line("🎨 Theme: <fg=cyan>{$theme}</>");
        $this->line("📁 Path: <fg=cyan>{$uiPath}</>");
        $this->info('');
        $this->info('Press Ctrl+C to stop the server');
        $this->info('');

        // Use Symfony Process for safer command execution
        $process = new Process(['php', '-S', "{$host}:{$port}", '-t', $uiPath]);

        if (Process::isTtySupported()) {
            $process->setTty(true);
        }

        // Run the process and return its exit code
        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * Validate host to prevent injection attacks
     */
    private function isValidHost(string $host): bool
    {
        // Allow localhost, 127.0.0.1, ::1 (IPv6 localhost), or valid IP addresses
        return filter_var($host, FILTER_VALIDATE_IP) !== false ||
               $host === 'localhost' ||
               $host === '::1';
    }

    /**
     * Validate port to ensure it's a valid port number
     */
    private function isValidPort($port): bool
    {
        if (is_int($port)) {
            return $port > 0 && $port <= 65535;
        }

        return is_string($port)
            && preg_match('/^\d+$/', $port) === 1
            && (int) $port > 0
            && (int) $port <= 65535;
    }

    protected function formatOutput(string $text, string $style): string
    {
        return "<{$style}>{$text}</>";
    }
}
