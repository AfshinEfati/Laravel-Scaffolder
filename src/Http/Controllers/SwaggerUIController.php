<?php

namespace Efati\ModuleGenerator\Http\Controllers;

use Efati\ModuleGenerator\Support\SwaggerConfigManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SwaggerUIController
{
    /**
     * Display Swagger UI
     */
    public function index()
    {
        $uiPath = storage_path('swagger-ui/index.html');

        if (!File::exists($uiPath)) {
            return response('Swagger UI not initialized. Run: php artisan swagger:init', 404)
                ->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        return response(File::get($uiPath), 200)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Serve swagger.json
     *
     * Note: Consider protecting this endpoint with authentication middleware
     * if your API documentation contains sensitive information.
     *
     * Example in routes:
     * Route::middleware(['auth:api'])->group(function () {
     *     Route::get('/api/swagger.json', [SwaggerUIController::class, 'spec']);
     * });
     */
    public function spec()
    {
        // Check if authentication is required for swagger spec
        $requiresAuth = config('module-generator.swagger.spec.secure', false);

        if ($requiresAuth && !$this->isAuthenticated()) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Authentication required to access API specification',
            ], 403);
        }

        $specPath = SwaggerConfigManager::specPath();

        if (!File::exists($specPath)) {
            return response()->json([
                'error' => 'Swagger spec not generated. Run: php artisan swagger:generate',
            ], 404);
        }

        return response()
            ->file($specPath)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Check if the current request is authenticated
     */
    private function isAuthenticated(): bool
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
