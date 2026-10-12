<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Admin boleh melakukan semua aksi pada post.
     * Mengembalikan null berarti "lanjut ke method policy di bawah".
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    /** Post terbit boleh dilihat semua user login; draft hanya pemiliknya (atau admin). */
    public function view(User $user, Post $post): bool
    {
        return $post->is_published || $post->user_id === $user->id;
    }

    /** Hanya editor (dan admin lewat before) yang boleh membuat post. */
    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    /** Editor hanya boleh mengubah post miliknya sendiri. */
    public function update(User $user, Post $post): bool
    {
        return $user->isEditor() && $post->user_id === $user->id;
    }

    /** Editor hanya boleh menghapus post miliknya sendiri. */
    public function delete(User $user, Post $post): bool
    {
        return $user->isEditor() && $post->user_id === $user->id;
    }
}
