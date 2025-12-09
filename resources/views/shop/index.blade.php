<x-public-layout>
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showFilters: false }">

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <div class="lg:hidden mb-4">
                <button @click="showFilters = !showFilters"
                    class="w-full flex items-center justify-center gap-2 bg-white border border-gray-200 py-3 rounded-xl shadow-sm text-hiyoucan-800 font-bold hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
            </div>

            <aside :class="{ 'block': showFilters, 'hidden': !showFilters }"
                class="hidden lg:block w-full lg:w-1/4 flex-shrink-0 transition-all duration-300" data-aos="fade-right">
                <form action="{{ route('shop.index') }}" method="GET"
                    class="sticky top-32 space-y-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-hiyoucan-900">Filters</h3>
                        <a href="{{ route('shop.index') }}"
                            class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wider">Reset</a>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Search</h4>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Find products..."
                                class="w-full pl-10 border-gray-200 bg-gray-50 rounded-lg text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 transition">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Categories</h4>
                        <div class="space-y-3">
                            @foreach ($categories as $category)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 shadow-sm checked:bg-hiyoucan-600 checked:border-hiyoucan-600 focus:ring-hiyoucan-500 transition">
                                        <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-gray-600 group-hover:text-hiyoucan-700 transition flex-1 text-sm font-medium">
                                        {{ $category->name }}
                                    </span>
                                    <span
                                        class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $category->products_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Price Range</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-500">Min</label>
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-hiyoucan-500"
                                    placeholder="0">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Max</label>
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                    class="w-full border-gray-200 rounded-lg text-sm focus:ring-hiyoucan-500"
                                    placeholder="Max">
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-hiyoucan-800 text-white py-3 rounded-xl font-bold hover:bg-hiyoucan-900 transition shadow-lg transform hover:-translate-y-1">
                        Apply Filters
                    </button>
                </form>
            </aside>

            <main class="w-full lg:w-3/4">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100"
                    data-aos="fade-down">
                    <p class="text-sm text-gray-500 font-medium mb-3 sm:mb-0">
                        Showing <span class="font-bold text-hiyoucan-900">{{ $products->count() }}</span> results
                    </p>
                    <div class="flex items-center space-x-3 w-full sm:w-auto">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Sort:</label>
                        <select onchange="window.location.href=this.value"
                            class="w-full sm:w-auto border-none bg-gray-50 text-sm font-bold text-gray-700 rounded-lg focus:ring-0 cursor-pointer hover:bg-gray-100 transition py-2 pl-3 pr-8">
                            <option
                                value="{{ route('shop.index', array_merge(request()->all(), ['sort' => 'newest'])) }}"
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option
                                value="{{ route('shop.index', array_merge(request()->all(), ['sort' => 'price_asc'])) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low</option>
                            <option
                                value="{{ route('shop.index', array_merge(request()->all(), ['sort' => 'price_desc'])) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    @forelse($products as $index => $product)
                        <div class="bg-white rounded-2xl p-3 sm:p-4 shadow-sm hover:shadow-xl transition-all duration-300 group relative border border-gray-100 flex flex-col h-full"
                            data-aos="zoom-in-up" data-aos-delay="{{ $index * 50 }}">

                            @if ($product->created_at->diffInDays() < 7)
                                <div class="absolute top-3 left-3 sm:top-6 sm:left-6 z-20 pointer-events-none">
                                    <span
                                        class="bg-hiyoucan-900 text-white text-[10px] font-bold px-2 py-0.5 sm:px-3 sm:py-1 rounded-full shadow-md uppercase tracking-wider">New</span>
                                </div>
                            @endif

                            @auth
                                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST"
                                    class="absolute top-3 right-3 sm:top-6 sm:right-6 z-30">
                                    @csrf
                                    <button type="submit"
                                        class="bg-white rounded-full p-1.5 sm:p-2 shadow-md hover:scale-110 transition duration-200 group/btn border border-gray-100">
                                        @php $isWishlisted = Auth::user()->wishlists->contains('product_id', $product->id); @endphp
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $isWishlisted ? 'fill-red-500 text-red-500' : 'fill-none text-gray-400 group-hover/btn:text-red-500' }}"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            @endauth

                            <a href="{{ route('shop.show', $product->slug) }}"
                                class="block relative aspect-[4/5] bg-earth-100 rounded-xl overflow-hidden mb-3 sm:mb-5">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                                <div
                                    class="hidden sm:flex absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition duration-300 items-center justify-center">
                                    <span
                                        class="bg-white text-hiyoucan-900 px-6 py-2 rounded-full text-sm font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300">View
                                        Details</span>
                                </div>
                            </a>

                            <div class="flex flex-col flex-grow">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-1 sm:mb-2 gap-1">
                                    <p
                                        class="text-[10px] sm:text-xs font-bold text-hiyoucan-600 uppercase tracking-wide truncate w-full">
                                        {{ $product->category->name ?? 'General' }}</p>
                                    <div
                                        class="flex items-center text-yellow-400 text-[10px] sm:text-xs gap-1 bg-yellow-50 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-gray-700 font-bold">{{ $product->average_rating }}</span>
                                    </div>
                                </div>

                                <h3
                                    class="font-bold text-gray-900 text-sm sm:text-lg mb-1 sm:mb-2 leading-tight group-hover:text-hiyoucan-700 transition line-clamp-2">
                                    {{ $product->name }}</h3>

                                <div
                                    class="mt-auto pt-2 sm:pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-sm sm:text-xl text-hiyoucan-900 font-bold">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('shop.show', $product->slug) }}"
                                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-earth-100 flex items-center justify-center text-hiyoucan-800 hover:bg-hiyoucan-800 hover:text-white transition shadow-sm">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-2xl shadow-sm border border-dashed border-gray-300">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="text-lg font-bold text-gray-900">No products found</h3>
                            <p class="text-gray-500">Try adjusting your search or filters.</p>
                            <a href="{{ route('shop.index') }}"
                                class="text-hiyoucan-700 font-bold hover:underline mt-4 block">Clear All Filters</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </main>
        </div>
    </div>
</x-public-layout>
