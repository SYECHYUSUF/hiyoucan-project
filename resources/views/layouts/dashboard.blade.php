<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hiyoucan Dashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased">
    
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-hiyoucan-900 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0">
            
            <div class="flex items-center justify-center h-16 bg-hiyoucan-800 border-b border-hiyoucan-700">
                <a href="/" class="text-xl font-bold tracking-widest uppercase">Hiyoucan.</a>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                
                @if(Auth::user()->role === 'admin')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Administration</p>
                    
                    <a href="{{ route('admin.home') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.home') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Dashboard
                    </a>
                
                    <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Manage Users
                    </a>
                    <a href="{{ route('admin.products') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.products') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Manage Products
                    </a>
                    <a href="{{ route('admin.categories') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.categories') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Categories
                    </a>
                @endif

                @if(Auth::user()->role === 'seller')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Seller Center</p>
                    
                    <a href="{{ route('seller.home') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('seller.home') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Dashboard
                    </a>
                    <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('seller.products.*') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> My Products
                    </a>
                    <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('seller.orders') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Orders
                    </a>
                    <a href="{{ route('seller.store.edit') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('seller.store.edit') ? 'bg-hiyoucan-700 text-white' : 'text-gray-300 hover:bg-hiyoucan-800 hover:text-white' }}">
                        <span class="mr-3"></span> Store Profile
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mt-8 pt-8 border-t border-hiyoucan-700">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-3 text-sm font-medium text-red-400 rounded-lg hover:bg-hiyoucan-800 hover:text-red-300">
                        <span class="mr-3"></span> Log Out
                    </button>
                </form>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between p-4 bg-white shadow-sm md:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="font-bold text-hiyoucan-900">Hiyoucan</span>
                <div class="w-6"></div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8">
                @if (isset($header))
                    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
                        {{ $header }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 md:hidden" style="display: none;"></div>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800, 
    once: true,    
    offset: 100,   
  });
</script>
</body>
</html> 