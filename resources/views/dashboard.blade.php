<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-900">
                <p>
                    Halo, <strong>{{ Auth::user()->name }}</strong>!
                    Role Anda:
                    <span class="inline-block px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 text-sm font-semibold">
                        {{ Auth::user()->role }}
                    </span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('products.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-semibold">🛍️ Katalog Produk</h3>
                    <p class="text-sm text-gray-600">Lihat produk yang tersedia (publik).</p>
                </a>

                <a href="{{ route('posts.index') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-semibold">📰 Posts</h3>
                    <p class="text-sm text-gray-600">Berita &amp; promo toko. Aksi edit/hapus dibatasi PostPolicy.</p>
                </a>

                @if (Auth::user()->hasRole('admin', 'editor'))
                    <a href="{{ route('editor.dashboard') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                        <h3 class="font-semibold">✍️ Area Editor</h3>
                        <p class="text-sm text-gray-600">Khusus admin &amp; editor.</p>
                    </a>
                @endif

                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="block bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                        <h3 class="font-semibold">🔐 Area Admin</h3>
                        <p class="text-sm text-gray-600">Khusus admin: statistik &amp; daftar user.</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
