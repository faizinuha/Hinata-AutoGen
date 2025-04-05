<?php

namespace Hinata\HikariAutogen\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeAll extends Command
{
    protected $signature = 'make:all {name} {--api : Generate API resources}';
    protected $description = 'Generate Model, Migration, Factory, Seeder, Controller dan komponen API';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $isApi = $this->option('api');

        $this->info("🔨 Membuat resources untuk: $name...");

        // Generate Model
        Artisan::call("make:model $name");
        $this->info("✅ Model $name berhasil dibuat!");

        // Generate Migration
        Artisan::call("make:migration create_{$this->snakeCase($name)}s_table");
        $this->info("✅ Migration untuk $name berhasil dibuat!");

        // Generate Factory
        Artisan::call("make:factory {$name}Factory --model=$name");
        $this->info("✅ Factory {$name}Factory berhasil dibuat!");

        // Generate Seeder
        Artisan::call("make:seeder {$name}Seeder");
        $this->info("✅ Seeder {$name}Seeder berhasil dibuat!");

        // Generate Views
        $this->createViews($name);
        $this->info("✅ Views untuk $name berhasil dibuat!");

        if ($isApi) {
            // Generate API Controller
            Artisan::call("make:controller Api/{$name}Controller --api");
            $this->info("✅ API Controller {$name}Controller berhasil dibuat!");

            // Generate Resource
            Artisan::call("make:resource {$name}Resource");
            $this->info("✅ Resource {$name}Resource berhasil dibuat!");

            // Generate Request
            Artisan::call("make:request {$name}Request");
            $this->createIndonesianValidation($name);
            $this->info("✅ Form Request {$name}Request berhasil dibuat dengan validasi bahasa Indonesia!");
            
            // Generate Notification
            Artisan::call("make:notification {$name}Notification");
            $this->createIndonesianNotification($name);
            $this->info("✅ Notification {$name}Notification berhasil dibuat!");
            
            // Generate Event
            Artisan::call("make:event {$name}Event");
            $this->info("✅ Event {$name}Event berhasil dibuat!");
            
            // Generate Listener
            Artisan::call("make:listener {$name}Listener --event={$name}Event");
            $this->info("✅ Listener {$name}Listener berhasil dibuat!");
        } else {
            // Generate regular Controller
            Artisan::call("make:controller {$name}Controller --resource");
            $this->info("✅ Controller {$name}Controller berhasil dibuat!");
        }

        $this->info("🎉 Semua resources untuk $name telah berhasil dibuat!");
    }

    protected function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    protected function createIndonesianValidation($name)
    {
        $requestPath = app_path("Http/Requests/{$name}Request.php");
        if (file_exists($requestPath)) {
            $content = file_get_contents($requestPath);
            
            // Tambahkan pesan validasi bahasa Indonesia
            $content = str_replace(
                "return false;",
                "return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'min' => ':attribute minimal harus :min karakter.',
            'unique' => ':attribute sudah digunakan.',
            'email' => ':attribute harus berupa alamat email yang valid.',
        ];",
                $content
            );
            
            file_put_contents($requestPath, $content);
        }
    }

    protected function createIndonesianNotification($name)
    {
        $notifPath = app_path("Notifications/{$name}Notification.php");
        if (file_exists($notifPath)) {
            $content = file_get_contents($notifPath);
            
            // Tambahkan template notifikasi bahasa Indonesia
            $content = str_replace(
                "public function toArray(\$notifiable)",
                "public function toMail(\$notifiable)
    {
        return (new MailMessage)
                    ->subject('Pemberitahuan ' . \$this->name)
                    ->greeting('Halo!')
                    ->line('Ada pembaruan untuk Anda.')
                    ->action('Lihat Detail', url('/'))
                    ->line('Terima kasih telah menggunakan aplikasi kami.');
    }

    public function toArray(\$notifiable)",
                $content
            );
            
            file_put_contents($notifPath, $content);
        }
    }

    protected function createViews($name)
    {
        $viewPath = resource_path("views/" . strtolower($name));
        if (!file_exists($viewPath)) {
            mkdir($viewPath, 0755, true);
        }

        // Create index view
        $indexView = "$viewPath/index.blade.php";
        file_put_contents($indexView, $this->getIndexViewTemplate($name));

        // Create create view
        $createView = "$viewPath/create.blade.php";
        file_put_contents($createView, $this->getCreateViewTemplate($name));

        // Create edit view
        $editView = "$viewPath/edit.blade.php";
        file_put_contents($editView, $this->getEditViewTemplate($name));

        // Create show view
        $showView = "$viewPath/show.blade.php";
        file_put_contents($showView, $this->getShowViewTemplate($name));
    }

    protected function getIndexViewTemplate($name)
    {
        return "@extends('layouts.app')

@section('content')
<div class='container'>
    <h1>{$name} List</h1>
    <a href='{{ route('" . strtolower($name) . ".create') }}' class='btn btn-primary mb-3'>Tambah {$name}</a>
    
    <div class='table-responsive'>
        <table class='table table-striped'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\${$name} as \$item)
                <tr>
                    <td>{{ \$item->id }}</td>
                    <td>{{ \$item->name }}</td>
                    <td>
                        <a href='{{ route('" . strtolower($name) . ".show', \$item->id) }}' class='btn btn-info btn-sm'>Detail</a>
                        <a href='{{ route('" . strtolower($name) . ".edit', \$item->id) }}' class='btn btn-warning btn-sm'>Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection";
    }

    protected function getCreateViewTemplate($name)
    {
        return "@extends('layouts.app')

@section('content')
<div class='container'>
    <h1>Tambah {$name}</h1>
    
    <form action='{{ route('" . strtolower($name) . ".store') }}' method='POST'>
        @csrf
        <div class='form-group'>
            <label>Nama</label>
            <input type='text' name='name' class='form-control' required>
        </div>
        <button type='submit' class='btn btn-primary'>Simpan</button>
    </form>
</div>
@endsection";
    }

    protected function getEditViewTemplate($name)
    {
        return "@extends('layouts.app')

@section('content')
<div class='container'>
    <h1>Edit {$name}</h1>
    
    <form action='{{ route('" . strtolower($name) . ".update', \${$name}->id) }}' method='POST'>
        @csrf
        @method('PUT')
        <div class='form-group'>
            <label>Nama</label>
            <input type='text' name='name' class='form-control' value='{{ \${$name}->name }}' required>
        </div>
        <button type='submit' class='btn btn-primary'>Update</button>
    </form>
</div>
@endsection";
    }

    protected function getShowViewTemplate($name)
    {
        return "@extends('layouts.app')

@section('content')
<div class='container'>
    <h1>Detail {$name}</h1>
    
    <div class='card'>
        <div class='card-body'>
            <h5 class='card-title'>{{ \${$name}->name }}</h5>
            <a href='{{ route('" . strtolower($name) . ".edit', \${$name}->id) }}' class='btn btn-warning'>Edit</a>
            <form action='{{ route('" . strtolower($name) . ".destroy', \${$name}->id) }}' method='POST' class='d-inline'>
                @csrf
                @method('DELETE')
                <button type='submit' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection";
    }
}
