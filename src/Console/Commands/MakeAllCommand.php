<?php

namespace Hinata\HikariAutogen\Console\Commands;

use Illuminate\Console\Command;

class MakeAllCommand extends Command
{
    protected $signature = 'make:all {name}';
    protected $description = 'Create a new model, controller, migration, and other related files';

    public function handle()
    {
        $name = $this->argument('name');

        $this->call('make:model', ['name' => $name, '--migration' => true, '--controller' => true, '--resource' => true]);
        $this->call('make:factory', ['name' => $name . 'Factory']);
        $this->call('make:seeder', ['name' => $name . 'Seeder']);
        $this->call('make:request', ['name' => $name . 'Request']);
        $this->call('make:resource', ['name' => $name . 'Resource']);

        $this->info('All related files for ' . $name . ' have been created successfully.');
    }
}
