<?php

namespace Efati\ModuleGenerator\Tests\Integration;

use Efati\ModuleGenerator\ModuleGeneratorServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\TestCase;

class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ModuleGeneratorServiceProvider::class,
        ];
    }

    public function testPackageCommandsAreRegistered(): void
    {
        $commands = Artisan::all();

        $this->assertArrayHasKey('make:module', $commands);
        $this->assertArrayHasKey('make:swagger', $commands);
        $this->assertArrayHasKey('swagger:init', $commands);
        $this->assertArrayHasKey('swagger:config', $commands);
        $this->assertArrayHasKey('swagger:generate', $commands);
        $this->assertArrayHasKey('swagger:ui', $commands);
    }

    public function testPublishGroupsAreRegistered(): void
    {
        $defaultPublishables = ServiceProvider::pathsToPublish(
            ModuleGeneratorServiceProvider::class,
            'module-generator'
        );
        $stubPublishables = ServiceProvider::pathsToPublish(
            ModuleGeneratorServiceProvider::class,
            'module-generator-stubs'
        );

        $this->assertNotEmpty($defaultPublishables);
        $this->assertNotEmpty($stubPublishables);
        $this->assertContains(config_path('module-generator.php'), $defaultPublishables);
        $this->assertContains(resource_path('stubs/module-generator'), $stubPublishables);
    }

    public function testBootDoesNotPublishApplicationFilesAutomatically(): void
    {
        $this->assertFileDoesNotExist(config_path('module-generator.php'));
        $this->assertFileDoesNotExist(app_path('Repositories/Eloquent/BaseRepository.php'));
        $this->assertFileDoesNotExist(app_path('Services/BaseService.php'));
        $this->assertFileDoesNotExist(resource_path('stubs/module-generator'));
    }
}
