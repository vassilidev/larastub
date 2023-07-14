<?php

namespace Vassilidev\Larastub;

use Illuminate\Support\ServiceProvider;
use Vassilidev\Larastub\Commands\InstallLarastubCommand;
use Vassilidev\Larastub\Commands\LarastubExecuteCommand;
use Vassilidev\Larastub\Commands\UninstallLarastubCommand;

class LarastubServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/larastub.php',
            'larastub'
        );
    }

    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        if (!function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/larastub.php' => config_path('larastub.php'),
        ], 'larastub-config');

        $this->publishes([
            __DIR__ . '/../stubs/' => template_path(),
        ], 'larastub-stubs');
    }

    protected function registerCommands(): void
    {
        $this->commands([
            InstallLarastubCommand::class,
            UninstallLarastubCommand::class,
            LarastubExecuteCommand::class,
        ]);
    }
}
