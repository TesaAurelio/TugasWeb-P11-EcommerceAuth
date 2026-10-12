<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan penting: users -> categories -> products -> aktivitas toko -> posts.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ShopActivitySeeder::class,
            PostSeeder::class,
        ]);
    }
}
