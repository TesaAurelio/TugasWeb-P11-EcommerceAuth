<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editor Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Post terbaru milik Anda</h3>
                <ul class="text-sm divide-y">
                    @forelse ($posts as $post)
                        <li class="py-2 flex justify-between">
                            <a href="{{ route('posts.show', $post) }}" class="text-indigo-700 hover:underline">{{ $post->title }}</a>
                            <span class="text-gray-500">{{ $post->is_published ? 'terbit' : 'draft' }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Belum ada post.</li>
                    @endforelse
                </ul>
                <a href="{{ route('posts.create') }}" class="inline-block mt-4 text-sm text-indigo-700 hover:underline">
                    + Tulis post baru
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
