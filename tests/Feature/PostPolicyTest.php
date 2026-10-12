<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    private array $payload = [
        'title' => 'Judul Baru',
        'body' => 'Isi baru post.',
        'is_published' => 1,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_editor_can_update_and_delete_own_post(): void
    {
        $editor = User::factory()->editor()->create();
        $post = Post::factory()->create(['user_id' => $editor->id]);

        $this->actingAs($editor)->put("/posts/{$post->id}", $this->payload)->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Judul Baru']);

        $this->actingAs($editor)->delete("/posts/{$post->id}")->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_editor_cannot_update_or_delete_other_editors_post(): void
    {
        $editorA = User::factory()->editor()->create();
        $editorB = User::factory()->editor()->create();
        $post = Post::factory()->published()->create(['user_id' => $editorB->id]);

        $this->actingAs($editorA)->get("/posts/{$post->id}/edit")->assertForbidden();
        $this->actingAs($editorA)->put("/posts/{$post->id}", $this->payload)->assertForbidden();
        $this->actingAs($editorA)->delete("/posts/{$post->id}")->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => $post->title]);
    }

    public function test_regular_user_cannot_edit_delete_or_create_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create();

        $this->actingAs($user)->get("/posts/{$post->id}")->assertOk(); // boleh baca post terbit
        $this->actingAs($user)->put("/posts/{$post->id}", $this->payload)->assertForbidden();
        $this->actingAs($user)->delete("/posts/{$post->id}")->assertForbidden();
        $this->actingAs($user)->post('/posts', $this->payload)->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_regular_user_cannot_read_draft_posts(): void
    {
        $user = User::factory()->create();
        $draft = Post::factory()->draft()->create();

        $this->actingAs($user)->get("/posts/{$draft->id}")->assertForbidden();
    }

    public function test_admin_can_update_and_delete_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        $this->actingAs($admin)->put("/posts/{$post->id}", $this->payload)->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Judul Baru']);

        $this->actingAs($admin)->delete("/posts/{$post->id}")->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_editor_can_create_post(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->post('/posts', $this->payload)->assertRedirect();

        $this->assertDatabaseHas('posts', ['title' => 'Judul Baru', 'user_id' => $editor->id]);
    }
}
