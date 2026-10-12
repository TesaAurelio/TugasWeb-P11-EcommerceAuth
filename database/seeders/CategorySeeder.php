<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public const CATEGORIES = [
        'Elektronik' => 'Gadget dan aksesoris elektronik harian.',
        'Fashion Pria' => 'Pakaian dan aksesoris untuk pria.',
        'Fashion Wanita' => 'Pakaian dan aksesoris untuk wanita.',
        'Perlengkapan Rumah' => 'Peralatan dapur, kamar tidur, dan rumah tangga.',
        'Olahraga' => 'Perlengkapan olahraga dan kebugaran.',
        'Buku & Alat Tulis' => 'Alat tulis, catatan, dan perlengkapan belajar.',
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}
