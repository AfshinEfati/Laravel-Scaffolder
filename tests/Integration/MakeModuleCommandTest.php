<?php

namespace Efati\ModuleGenerator\Tests\Integration;

use Efati\ModuleGenerator\ModuleGeneratorServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class MakeModuleCommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ModuleGeneratorServiceProvider::class,
        ];
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(app_path('ScaffolderIntegration'));

        parent::tearDown();
    }

    public function testItGeneratesRepositoryAndServiceLayersFromInlineSchema(): void
    {
        config()->set('module-generator.paths.repository', [
            'eloquent' => 'ScaffolderIntegration/Repositories/Eloquent',
            'contracts' => 'ScaffolderIntegration/Repositories/Contracts',
        ]);
        config()->set('module-generator.paths.service', [
            'concretes' => 'ScaffolderIntegration/Services',
            'contracts' => 'ScaffolderIntegration/Services/Contracts',
        ]);

        $exitCode = Artisan::call('make:module', [
            'name' => 'PortfolioSample',
            '--fields' => 'name:string,is_active:boolean',
            '--no-controller' => true,
            '--no-resource' => true,
            '--no-dto' => true,
            '--no-test' => true,
            '--no-provider' => true,
            '--no-actions' => true,
            '--no-policy' => true,
            '--no-swagger' => true,
        ]);

        $this->assertSame(0, $exitCode, Artisan::output());
        $this->assertFileExists(app_path('ScaffolderIntegration/Repositories/Eloquent/PortfolioSampleRepository.php'));
        $this->assertFileExists(app_path('ScaffolderIntegration/Repositories/Contracts/PortfolioSampleRepositoryInterface.php'));
        $this->assertFileExists(app_path('ScaffolderIntegration/Services/PortfolioSampleService.php'));
        $this->assertFileExists(app_path('ScaffolderIntegration/Services/Contracts/PortfolioSampleServiceInterface.php'));

        $repository = File::get(
            app_path('ScaffolderIntegration/Repositories/Eloquent/PortfolioSampleRepository.php')
        );
        $service = File::get(
            app_path('ScaffolderIntegration/Services/PortfolioSampleService.php')
        );

        $this->assertStringContainsString('class PortfolioSampleRepository', $repository);
        $this->assertStringContainsString('class PortfolioSampleService', $service);
    }
}
