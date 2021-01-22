<?php

namespace SantosPierre\LaravelAuthAPI;

use Illuminate\Filesystem\Filesystem;
use Laravel\Ui\Presets\Preset;

class LaravelAuthAPIPreset extends Preset
{
    public static function install()
    {
        $filesystem = new Filesystem();
        // Sanctum Setup
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Kernel.php'), app_path('Http/Kernel.php'));
        static::updateFile(base_path('app/Http/Middleware/Authenticate.php'), function ($file) {
            return str_replace("return route('login');", "return url(env('SPA_URL') . '/login');", $file);
        });
        // Config File
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Sanctum/config.php'), config_path('sanctum.php'));
        // Fortify Setup
        static::updateFile(base_path('app/Http/Middleware/Authenticate.php'), function ($file) {
            return str_replace("return route('login');", "return url(env('SPA_URL') . '/login');", $file);
        });
        // Auth Provider
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Fortify/AuthServiceProvider.php'), app_path('Providers/AuthServiceProvider.php'));
        // Config File
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Fortify/config/fortify.php'), config_path('fortify.php'));
        // CORS Setup
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/cors.php'), config_path('cors.php'));
        // User Resource
        $filesystem->copyDirectory(base_path('LaravelAuthAPI/stubs/Resources'), app_path('Http/Resources'));
        // Socialite Service
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Socialite/service.php'), config_path('service.php'));
        // Login Controller
        $filesystem->copy(base_path('LaravelAuthAPI/stubs/Socialite/LoginController.php'), app_path('Http/Controllers/LoginController.php'));
        // Routes
        static::updateFile(base_path('routes/api.php'), function ($file) {
            return str_replace("auth:api", "auth:sanctum", $file);
        });
        static::updateFile(base_path('routes/api.php'), function ($file) {
            return str_replace("return \$request->user();", "return new UserResource(\$request->user());", $file);
        });
        $filesystem->append(base_path('routes/web.php'), $filesystem->get(base_path('LaravelAuthAPI/stubs/Routes/web.php')));
        // Environment File
        $filesystem->append(base_path('.env'), $filesystem->get(base_path('LaravelAuthAPI/stubs/.env')));
    }

    /**
     * Update the contents of a file with the logic of a given callback.
     */
    protected static function updateFile(string $path, callable $callback)
    {
        $originalFileContents = file_get_contents($path);
        $newFileContents = $callback($originalFileContents);
        file_put_contents($path, $newFileContents);
    }
}
