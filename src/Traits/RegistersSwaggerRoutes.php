<?php

namespace Efati\ModuleGenerator\Traits;

use Efati\ModuleGenerator\Support\SwaggerConfigManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Router;

trait RegistersSwaggerRoutes
{
    /**
     * Register Swagger documentation routes
     *
     * Usage in routes/api.php:
     * use Efati\ModuleGenerator\Traits\RegistersSwaggerRoutes;
     *
     * Route::middleware(['api'])->group(function () {
     *     Route::registerSwaggerRoutes();
     * });
     *
     * To protect the swagger.json endpoint with authentication:
     * Route::middleware(['api', 'auth:api'])->group(function () {
     *     Route::registerSwaggerRoutes();
     * });
     */
    public static function registerSwaggerRoutes(): void
    {
        $router = app(Router::class);

        $router->group(['prefix' => 'docs', 'name' => 'api.docs.'], function ($router) {
            $router->get('/', function () {
                $uiPath = storage_path('swagger-ui/index.html');

                if (!\Illuminate\Support\Facades\File::exists($uiPath)) {
                    return response()->json([
                        'error' => 'Swagger UI not initialized',
                        'message' => 'Run: php artisan swagger:init',
                    ], 404);
                }

                return response()
                    ->file($uiPath)
                    ->header('Content-Type', 'text/html');
            })->name('index');

            $router->get('swagger.json', function () {
                // Support optional authentication requirement via config
                $requiresAuth = config('module-generator.swagger.spec.secure', false);

                if ($requiresAuth && !self::isAuthenticated()) {
                    return response()->json([
                        'error' => 'Unauthorized',
                        'message' => 'Authentication required to access API specification',
                    ], 403);
                }

                $specPath = SwaggerConfigManager::specPath();

                if (!\Illuminate\Support\Facades\File::exists($specPath)) {
                    return response()->json([
                        'error' => 'Swagger spec not generated',
                        'message' => 'Run: php artisan swagger:generate',
                    ], 404);
                }

                return response()
                    ->file($specPath)
                    ->header('Content-Type', 'application/json');
            })->name('spec');
        });
    }

    /**
     * Check if the current request is authenticated
     */
    private static function isAuthenticated(): bool
    {
        foreach (array_keys((array) config('auth.guards', [])) as $guard) {
            try {
                if (Auth::guard($guard)->check()) {
                    return true;
                }
            } catch (\Throwable) {
                // Ignore unavailable or incomplete optional guards.
            }
        }

        return false;
    }
}
