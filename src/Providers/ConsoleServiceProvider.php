<?php

namespace Hinata\HikariAutogen\Providers;

use Illuminate\Support\ServiceProvider;
use Hinata\HikariAutogen\Console\Commands\MakeAllCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $commands = [
        MakeAllCommand::class,
    ];

    public function register()
    {
        $this->commands($this->commands);
    }

    public function boot()
    {
        // ...existing code...
    }
}