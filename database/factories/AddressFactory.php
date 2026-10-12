<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        $places = [
            ['Medan', 'Sumatera Utara', '20111'],
            ['Jakarta Selatan', 'DKI Jakarta', '12110'],
            ['Bandung', 'Jawa Barat', '40115'],
            ['Surabaya', 'Jawa Timur', '60241'],
            ['Yogyakarta', 'DI Yogyakarta', '55281'],
            ['Makassar', 'Sulawesi Selatan', '90111'],
        ];
        [$city, $province, $postal] = fake()->randomElement($places);

        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Kos']),
            'recipient_name' => fake('id_ID')->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'street' => 'Jl. '.fake()->streetName().' No. '.fake()->numberBetween(1, 150),
            'city' => $city,
            'province' => $province,
            'postal_code' => $postal,
            'is_default' => false,
        ];
    }
}
