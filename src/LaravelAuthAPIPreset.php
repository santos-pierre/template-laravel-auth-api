<?php

namespace SantosPierre\LaravelAuthAPI;

use Illuminate\Filesystem\Filesystem;
use Laravel\Ui\Presets\Preset;

class LaravelAuthAPIPreset extends Preset
{
    public static function install()
    {
        $filesystem = new Filesystem();
        $filesystem->copyDirectory(__DIR__ . '/../stubs', base_path());
        // Sanctum Setup
        static::updateFile(base_path('app/Http/Middleware/Authenticate.php'), function ($file) {
            return str_replace("return route('login');", "return url(env('SPA_URL') . '/login');", $file);
        });
        // Environment File
        $filesystem->append(base_path('.env'), $filesystem->get(__DIR__ . '/../.env.example'));
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
