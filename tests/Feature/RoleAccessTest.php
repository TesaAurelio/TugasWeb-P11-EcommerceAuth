<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tidak perlu `npm run build` agar test bisa jalan
        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login_on_protected_routes(): void
    {
        foreach (['/dashboard', '/admin/dashboard', '/admin/users', '/editor/dashboard', '/posts', '/posts/create'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_guest_can_view_public_product_catalog(): void
    {
        Product::factory()->create(['name' => 'Produk Uji', 'stock' => 5, 'is_active' => true]);

        $this->get('/products')->assertOk()->assertSee('Produk Uji');
    }

    public function test_catalog_hides_inactive_and_out_of_stock_products(): void
    {
        Product::factory()->create(['name' => 'Produk Tampil', 'stock' => 5]);
        Product::factory()->inactive()->create(['name' => 'Produk Nonaktif', 'stock' => 5]);
        Product::factory()->outOfStock()->create(['name' => 'Produk Habis']);

        $this->get('/products')
            ->assertSee('Produk Tampil')
            ->assertDontSee('Produk Nonaktif')
            ->assertDontSee('Produk Habis');
    }

    public function test_regular_user_is_forbidden_from_admin_and_editor_areas(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/posts')->assertOk();
        $this->actingAs($user)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get('/editor/dashboard')->assertForbidden();
        $this->actingAs($user)->get('/posts/create')->assertForbidden();
    }

    public function test_editor_can_access_editor_area_but_not_admin_area(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get('/editor/dashboard')->assertOk();
        $this->actingAs($editor)->get('/posts/create')->assertOk();
        $this->actingAs($editor)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
    }

    public function test_admin_can_access_all_areas(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (['/dashboard', '/admin/dashboard', '/admin/users', '/editor/dashboard', '/posts', '/posts/create'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_new_registration_always_gets_user_role(): void
    {
        // Mencoba menyisipkan role=admin lewat form register harus diabaikan
        $this->post('/register', [
            'name' => 'Penyusup',
            'email' => 'penyusup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'penyusup@example.com',
            'role' => 'user',
        ]);
    }
}
