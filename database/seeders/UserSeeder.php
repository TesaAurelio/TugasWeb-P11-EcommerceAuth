<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo untuk testing 3 role (password semuanya: "password")
        User::factory()->admin()->create([
            'name' => 'Admin Toko',
            'email' => 'admin@example.com',
        ]);

        User::factory()->editor()->create([
            'name' => 'Editor Konten',
            'email' => 'editor@example.com',
        ]);

        User::factory()->create([
            'name' => 'Pelanggan Demo',
            'email' => 'user@example.com',
        ]);

        // Pelanggan tambahan untuk data pesanan & review
        User::factory()->count(10)->create();
    }
}
