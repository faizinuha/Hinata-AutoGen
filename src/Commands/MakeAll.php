<?php

namespace Hinata\HikariAutogen\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MakeAll extends Command
{
    protected $signature = 'make:all {name}';
    protected $description = 'Generate Model, Migration, Factory, Seeder, and Controller';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $this->info("🔨 Generating resources for: $name...");

        // Generate Model
        Artisan::call("make:model $name");
        $this->info("✅ Model $name created!");

        // Generate Migration
        Artisan::call("make:migration create_{$this->snakeCase($name)}s_table");
        $this->info("✅ Migration for $name created!");

        // Generate Factory
        Artisan::call("make:factory {$name}Factory --model=$name");
        $this->info("✅ Factory {$name}Factory created!");

        // Generate Seeder
        Artisan::call("make:seeder {$name}Seeder");
        $this->info("✅ Seeder {$name}Seeder created!");

        // Generate Controller
        Artisan::call("make:controller {$name}Controller --resource");
        $this->info("✅ Controller {$name}Controller created!");

        $this->info("🎉 All resources for $name have been generated successfully!");
    }

    protected function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
