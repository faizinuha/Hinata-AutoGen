<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
// use Illuminate\Support\Facades\File;
// use Tests\TestCase;

class AutoGenServiceProviderTest extends TestCase
{
    /** @test */
    public function it_publishes_the_configuration_file()
    {
        // Hapus file konfigurasi jika ada
        if (File::exists(config_path('autogen.php'))) {
            File::delete(config_path('autogen.php'));
        }

        // Pastikan file konfigurasi tidak ada sebelum dipublikasikan
        $this->assertFalse(File::exists(config_path('autogen.php')));

        // Jalankan perintah vendor:publish
        Artisan::call('vendor:publish', ['--tag' => 'autogen', '--force' => true]);

        // Pastikan file konfigurasi dipublikasikan
        $this->assertTrue(File::exists(config_path('autogen.php')));
    }
}
