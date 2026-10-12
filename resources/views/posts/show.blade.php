<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <article class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500 mb-4">
                    oleh {{ $post->author->name }} ({{ $post->author->role }}) &middot; {{ $post->created_at->format('d M Y H:i') }}
                    @unless ($post->is_published)
                        <span class="ml-1 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">draft</span>
                    @endunless
                </p>

                <div class="text-gray-800 leading-relaxed">{!! nl2br(e($post->body)) !!}</div>
            </article>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('posts.index') }}" class="text-gray-600 hover:underline">&larr; Kembali</a>

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
    </div>
</x-app-layout>
