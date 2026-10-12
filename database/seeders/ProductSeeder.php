<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * 60 produk realistis (harga dalam Rupiah), 10 per kategori.
     * Format: 'Nama Produk' => harga
     */
    private const CATALOG = [
        'Elektronik' => [
            'Earbuds Bluetooth TWS' => 249000,
            'Power Bank 10000mAh' => 189000,
            'Mouse Wireless Silent Click' => 125000,
            'Keyboard Mekanik 87 Key' => 399000,
            'Webcam Full HD 1080p' => 275000,
            'Speaker Bluetooth Portable' => 215000,
            'Charger USB-C 33W' => 99000,
            'Kabel Data USB-C 1 Meter' => 35000,
            'Smartband Fitness Tracker' => 329000,
            'Lampu Meja LED Rechargeable' => 145000,
        ],
        'Fashion Pria' => [
            'Kemeja Flannel Lengan Panjang' => 159000,
            'Kaos Polos Cotton Combed 30s' => 69000,
            'Celana Chino Slim Fit' => 219000,
            'Jaket Bomber Waterproof' => 289000,
            'Hoodie Fleece Oversize' => 199000,
            'Celana Jeans Regular Fit' => 249000,
            'Sepatu Sneakers Casual' => 329000,
            'Ikat Pinggang Kulit' => 89000,
            'Kemeja Batik Lengan Pendek' => 175000,
            'Topi Baseball Polos' => 49000,
        ],
        'Fashion Wanita' => [
            'Blouse Satin Lengan Panjang' => 149000,
            'Dress Midi Motif Bunga' => 229000,
            'Rok Plisket Panjang' => 119000,
            'Cardigan Rajut Oversize' => 169000,
            'Hijab Pashmina Ceruty' => 59000,
            'Tas Selempang Kulit Sintetis' => 199000,
            'Celana Kulot Linen' => 139000,
            'Kemeja Oversize Wanita' => 129000,
            'Sandal Wedges Nyaman' => 159000,
            'Outer Blazer Crepe' => 259000,
        ],
        'Perlengkapan Rumah' => [
            'Rice Cooker 1.8 Liter' => 459000,
            'Set Panci Anti Lengket 3 Pcs' => 329000,
            'Vacuum Cleaner Handheld' => 389000,
            'Set Pisau Dapur Stainless 5 Pcs' => 199000,
            'Bantal Memory Foam' => 135000,
            'Sprei Katun Queen Size' => 249000,
            'Lampu Tidur LED Sensor Gerak' => 79000,
            'Rak Sepatu Susun 4 Tingkat' => 145000,
            'Botol Minum Stainless 750ml' => 95000,
            'Kotak Penyimpanan Plastik 3 Pcs' => 85000,
        ],
        'Olahraga' => [
            'Matras Yoga TPE 6mm' => 129000,
            'Dumbbell Vinyl 5kg (Sepasang)' => 189000,
            'Sepatu Lari Ringan' => 379000,
            'Jersey Olahraga Dry-Fit' => 99000,
            'Tali Skipping Speed Rope' => 45000,
            'Resistance Band Set 5 Level' => 79000,
            'Tas Gym Duffel' => 169000,
            'Raket Badminton Karbon' => 259000,
            'Sarung Tangan Gym' => 59000,
            'Botol Shaker Protein 600ml' => 49000,
        ],
        'Buku & Alat Tulis' => [
            'Buku Catatan Hardcover A5' => 55000,
            'Pulpen Gel 0.5mm Isi 12' => 49000,
            'Set Spidol Warna 24 Warna' => 89000,
            'Stabilo Highlighter Pastel Isi 6' => 39000,
            'Binder Notebook A5 + Refill' => 75000,
            'Papan Jalan Clipboard A4' => 25000,
            'Kalkulator Ilmiah 240 Fungsi' => 169000,
            'Buku Sketsa A4 120gsm' => 65000,
            'Lem Kertas Stik Isi 5' => 29000,
            'Tempat Pensil Kanvas Besar' => 35000,
        ],
    ];

    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $counter = 1;

        foreach (self::CATALOG as $categoryName => $items) {
            foreach ($items as $name => $price) {
                Product::factory()->create([
                    'category_id' => $categories[$categoryName],
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'sku' => sprintf('SKU-%04d', $counter),
                    'description' => "{$name} berkualitas, cocok untuk kebutuhan sehari-hari. Garansi toko 7 hari.",
                    'price' => $price,
                    // Sebagian kecil sengaja dibuat habis agar scope inStock() terlihat efeknya
                    'stock' => $counter % 13 === 0 ? 0 : random_int(5, 120),
                ]);
                $counter++;
            }
        }
    }
}
