<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_relationships_and_scopes_work(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'name' => 'Kemeja Uji', 'price' => 150000, 'stock' => 3]);
        Product::factory()->outOfStock()->create(['category_id' => $category->id]);
        Product::factory()->inactive()->create(['category_id' => $category->id]);

        $this->assertTrue($product->category->is($category));
        $this->assertCount(3, $category->products);
        $this->assertCount(1, Product::active()->inStock()->get());
        $this->assertCount(1, Product::search('kemeja')->get());
        $this->assertCount(1, Product::priceBetween(100000, 200000)->get());
    }

    public function test_order_has_items_products_and_user(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $order = Order::factory()->create(['user_id' => $user->id, 'address_id' => $address->id]);
        $product = Product::factory()->create(['price' => 100000]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);

        $order->refresh();

        $this->assertTrue($order->user->is($user));
        $this->assertTrue($order->address->is($address));
        $this->assertCount(1, $order->items);
        $this->assertSame(200000, $order->items->first()->subtotal());
        $this->assertSame(2, $order->products->first()->pivot->quantity);
    }

    public function test_foreign_key_prevents_deleting_category_with_products(): void
    {
        $product = Product::factory()->create();

        $this->expectException(QueryException::class);

        $product->category->delete();
    }

    public function test_user_can_review_a_product_only_once(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        Review::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);

        $this->expectException(QueryException::class);

        Review::factory()->create(['user_id' => $user->id, 'product_id' => $product->id]);
    }
}
