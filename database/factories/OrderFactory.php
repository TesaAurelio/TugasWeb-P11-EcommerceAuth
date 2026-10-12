<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $orderedAt = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'user_id' => User::factory(),
            'address_id' => null,
            'order_number' => 'INV-'.$orderedAt->format('Ymd').'-'.strtoupper(Str::random(6)),
            'status' => fake()->randomElement(Order::STATUSES),
            'total_amount' => 0, // dihitung ulang setelah order_items dibuat
            'ordered_at' => $orderedAt,
        ];
    }
}
