<?php

namespace Hinata\AutoGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Hinata\AutoGenerator\Commands\MakeAll;

class AutoGenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/autogen.php', 'autogen');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Register command
            $this->commands([
                MakeAll::class,
            ]);

            // Publish config
            $this->publishes([
                __DIR__ . '/../../config/autogen.php' => config_path('autogen.php'),
            ], 'autogen');
        }
    }
}
