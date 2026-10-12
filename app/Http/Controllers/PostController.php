<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Post::class);

        $user = $request->user();

        $posts = Post::with('author')
            ->when(! $user->isAdmin(), function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('is_published', true)->orWhere('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $post = $request->user()->posts()->create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(6)),
            'body' => $data['body'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('posts.show', $post)->with('status', 'Post berhasil dibuat.');
    }

    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        $post->load('author');

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $post->update([
            'title' => $data['title'],
            'body' => $data['body'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('posts.show', $post)->with('status', 'Post berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('status', 'Post berhasil dihapus.');
    }
}
