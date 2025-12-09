<nav x-data="{ open: false, profileOpen: false }" class="bg-white/90 backdrop-blur-md fixed w-full z-50 top-0 border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-hiyoucan-900 tracking-widest uppercase hover:text-hiyoucan-600 transition duration-300 transform hover:scale-105 mr-10">
                    Hiyoucan.
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="/" class="relative group text-gray-500 hover:text-hiyoucan-700 font-medium transition duration-300 {{ request()->is('/') ? 'text-hiyoucan-700 font-bold' : '' }}">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-hiyoucan-700 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('shop.index') }}" class="relative group text-gray-500 hover:text-hiyoucan-700 font-medium transition duration-300 {{ request()->routeIs('shop.*') ? 'text-hiyoucan-700 font-bold' : '' }}">
                        Shop
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-hiyoucan-700 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="{{ route('about') }}" class="relative group text-gray-500 hover:text-hiyoucan-700 font-medium transition duration-300 {{ request()->routeIs('about') ? 'text-hiyoucan-700 font-bold' : '' }}">
                        About
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-hiyoucan-700 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4 sm:space-x-6">
                
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-hiyoucan-700 relative transition transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    @auth
                    <span class="absolute -top-1 -right-1 bg-hiyoucan-700 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center animate-bounce">
                        {{ Auth::user()->carts()->count() }}
                    </span>
                    @endauth
                </a>

                <div class="hidden md:flex items-center">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role === 'buyer' || Auth::user()->role === 'user')
                                <div class="relative">
                                    <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-hiyoucan-700 focus:outline-none transition group">
                                        <div class="h-8 w-8 rounded-full bg-hiyoucan-100 flex items-center justify-center text-hiyoucan-700 font-bold border border-hiyoucan-200 group-hover:border-hiyoucan-500 transition">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="hidden lg:block group-hover:text-hiyoucan-900">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': profileOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="profileOpen" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-100"
                                         style="display: none;">
                                        
                                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                            <p class="text-xs text-gray-500">Signed in as</p>
                                            <p class="text-sm font-bold text-hiyoucan-900 truncate">{{ Auth::user()->email }}</p>
                                        </div>

                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hiyoucan-50 hover:text-hiyoucan-700 transition flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" /></svg>
                                            Edit Profile
                                        </a>
                                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hiyoucan-50 hover:text-hiyoucan-700 transition flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /></svg>
                                            My Wishlist
                                        </a>
                                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-hiyoucan-50 hover:text-hiyoucan-700 transition flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                                            My Orders
                                        </a>
                                        
                                        <div class="border-t border-gray-100 my-1"></div>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M9 12h12l-3 -3" /><path d="M18 15l3 -3" /></svg>
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-white bg-hiyoucan-700 px-5 py-2.5 rounded-full hover:bg-hiyoucan-800 transition shadow-lg hover:shadow-hiyoucan-500/30 transform hover:-translate-y-0.5">Dashboard</a>
                            @endif
                        @else
                            <div class="flex items-center gap-4">
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-hiyoucan-700 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-hiyoucan-700 px-5 py-2.5 rounded-full hover:bg-hiyoucan-800 transition shadow-lg hover:shadow-hiyoucan-500/30 transform hover:-translate-y-0.5">Register</a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>

                <div class="flex items-center md:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-hiyoucan-700 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-white border-b border-gray-200 shadow-lg absolute w-full z-40">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-hiyoucan-700 hover:bg-gray-50 {{ request()->is('/') ? 'bg-hiyoucan-50 text-hiyoucan-800' : '' }}">Home</a>
            <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-hiyoucan-700 hover:bg-gray-50 {{ request()->routeIs('shop.*') ? 'bg-hiyoucan-50 text-hiyoucan-800' : '' }}">Shop</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-hiyoucan-700 hover:bg-gray-50 {{ request()->routeIs('about') ? 'bg-hiyoucan-50 text-hiyoucan-800' : '' }}">About</a>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-200 px-4">
            @auth
                <div class="flex items-center px-3 mb-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-hiyoucan-100 flex items-center justify-center text-hiyoucan-700 font-bold border border-hiyoucan-200">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1">
                    @if(Auth::user()->role === 'buyer' || Auth::user()->role === 'user')
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Edit Profile</a>
                        <a href="{{ route('wishlist.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">My Wishlist</a>
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">My Orders</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Dashboard</a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                            Log Out
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-hiyoucan-700 hover:bg-hiyoucan-800">Register</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>