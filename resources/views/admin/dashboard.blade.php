<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Produk</p>
                    <p class="text-2xl font-bold">{{ $productCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Pesanan</p>
                    <p class="text-2xl font-bold">{{ $orderCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6 col-span-2">
                    <p class="text-sm text-gray-500">Pendapatan (paid/shipped/completed)</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">User per role</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    @foreach (\App\Models\User::ROLES as $role)
                        <li>{{ $role }}: <strong>{{ $userCounts[$role] ?? 0 }}</strong></li>
                    @endforeach
                </ul>
                <a href="{{ route('admin.users') }}" class="inline-block mt-4 text-sm text-indigo-700 hover:underline">
                    Lihat daftar user &rarr;
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
