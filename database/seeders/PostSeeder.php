<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $editor = User::where('email', 'editor@example.com')->firstOrFail();

        // Post milik admin dan editor (campuran terbit & draft) untuk menguji PostPolicy
        Post::factory()->count(4)->published()->create(['user_id' => $admin->id]);
        Post::factory()->count(1)->draft()->create(['user_id' => $admin->id]);

        Post::factory()->count(5)->published()->create(['user_id' => $editor->id]);
        Post::factory()->count(2)->draft()->create(['user_id' => $editor->id]);
    }
}
