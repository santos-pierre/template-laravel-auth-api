<?php

namespace SantosPierre\LaravelAuthAPI;

use Illuminate\Support\ServiceProvider;
use Laravel\Ui\UiCommand;

class LaraveAuthAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        UiCommand::macro('laravel-auth-api', function ($command) {
            LaravelAuthAPIPreset::install();
            $command->info('Installation Complete!');
            $command->comment('Migrate database => php artisan migrate or sail artisan migrate');
        });
    }
}
