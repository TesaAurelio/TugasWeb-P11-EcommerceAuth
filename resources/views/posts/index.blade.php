<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
            @can('create', App\Models\Post::class)
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md text-xs text-white uppercase font-semibold hover:bg-gray-700">
                    + Tulis Post
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @forelse ($posts as $post)
                    <div class="p-4 flex items-center justify-between gap-4">
                        <div>
                            <a href="{{ route('posts.show', $post) }}" class="font-semibold text-indigo-700 hover:underline">
                                {{ $post->title }}
                            </a>
                            <p class="text-sm text-gray-500">
                                oleh {{ $post->author->name }} ({{ $post->author->role }}) &middot;
                                {{ $post->created_at->diffForHumans() }}
                                @unless ($post->is_published)
                                    <span class="ml-1 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">draft</span>
                                @endunless
                            </p>
                        </div>

                        <div class="flex gap-3 text-sm">
                            @can('update', $post)
                                <a href="{{ route('posts.edit', $post) }}" class="text-blue-600 hover:underline">Edit</a>
                            @endcan
                            @can('delete', $post)
                                <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                      onsubmit="return confirm('Hapus post ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="p-6 text-gray-500">Belum ada post.</p>
                @endforelse
            </div>

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
