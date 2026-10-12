<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopActivitySeeder extends Seeder
{
    /**
     * Mengisi alamat, pesanan + item pesanan, review, dan keranjang untuk tiap pelanggan.
     */
    public function run(): void
    {
        $products = Product::all();

        User::where('role', User::ROLE_USER)->get()->each(function (User $user) use ($products) {
            $address = Address::factory()->create([
                'user_id' => $user->id,
                'recipient_name' => $user->name,
                'is_default' => true,
            ]);

            // 1-3 pesanan per pelanggan
            foreach (range(1, random_int(1, 3)) as $_) {
                $order = Order::factory()->create([
                    'user_id' => $user->id,
                    'address_id' => $address->id,
                ]);

                $total = 0;
                foreach ($products->random(random_int(1, 4)) as $product) {
                    $qty = random_int(1, 3);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $product->price,
                    ]);

                    $total += $qty * $product->price;
                }

                $order->update(['total_amount' => $total]);
            }

            // 3 review untuk produk berbeda (unik per user+produk)
            foreach ($products->random(3) as $product) {
                Review::factory()->create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }

            // 2 item di keranjang
            foreach ($products->random(2) as $product) {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => random_int(1, 2),
                ]);
            }
        });
    }
}
