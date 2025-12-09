<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Registered Buyers</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 {{ $stats['pending_sellers'] > 0 ? 'border-red-500' : 'border-gray-300' }}">
                <p class="text-sm text-gray-500">Pending Sellers</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_sellers'] }}</p>
                @if($stats['pending_sellers'] > 0)
                    <a href="{{ route('admin.users') }}" class="text-xs text-red-500 hover:underline font-bold mt-1 block">Review Candidates &rarr;</a>
                @endif
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="font-bold text-lg mb-2">Welcome back, Admin!</h3>
                <p class="text-gray-600">
                    Anda memiliki akses penuh untuk mengelola pengguna, kategori, memantau produk seller, dan melihat statistik platform Hiyoucan.
                </p>
            </div>
        </div>
    </div>
</x-dashboard-layout>