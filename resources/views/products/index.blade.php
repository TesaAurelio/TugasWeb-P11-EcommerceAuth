<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">🛍️ Katalog Produk</h1>
            <div class="text-sm space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-indigo-700 hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-indigo-700 hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="text-indigo-700 hover:underline">Register</a>
                @endauth
            </div>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama atau SKU..."
                   class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Cari</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col">
                    <span class="text-xs text-indigo-700 font-semibold">{{ $product->category->name }}</span>
                    <h2 class="font-semibold mt-1">{{ $product->name }}</h2>
                    <p class="text-xs text-gray-500 mt-1">{{ $product->sku }} &middot; stok {{ $product->stock }}</p>
                    <p class="mt-auto pt-4 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="col-span-full text-gray-500">Produk tidak ditemukan.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $products->links() }}</div>
    </div>
</body>
</html>
