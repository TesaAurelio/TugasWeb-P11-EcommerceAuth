<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => fake()->randomElement([3, 4, 4, 5, 5, 5, 2, 1]),
            'comment' => fake()->randomElement([
                'Barang sesuai deskripsi, pengiriman cepat.',
                'Kualitas bagus untuk harganya, recommended!',
                'Packing rapi dan aman. Terima kasih.',
                'Cukup oke, tapi warna sedikit berbeda dari foto.',
                'Sudah beli dua kali, selalu puas.',
                'Pengiriman agak lama, tapi barang bagus.',
            ]),
        ];
    }
}
