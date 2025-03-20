<?php

namespace Hikari\AutoGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Hikari\AutoGenerator\Commands\MakeAll;

class AutoGenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/autogen.php', 'autogen');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAll::class,
            ]);

            $this->publishes([
                __DIR__.'/../../config/autogen.php' => config_path('autogen.php'),
            ], 'autogen');
        }
    }
}
