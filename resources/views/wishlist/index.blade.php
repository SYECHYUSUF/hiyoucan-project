<x-public-layout>
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-bold text-hiyoucan-900">My Wishlist</h1>
            <a href="{{ route('shop.index') }}" class="text-hiyoucan-700 hover:underline text-sm font-medium">Browse More</a>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($wishlists as $index => $item)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition group relative overflow-hidden border border-gray-100 flex flex-col" 
                     data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    
                    <form action="{{ route('wishlist.toggle', $item->product->id) }}" method="POST" class="absolute top-4 right-4 z-20">
                        @csrf
                        <button type="submit" class="bg-white/90 backdrop-blur p-2 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm" title="Remove">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                        </button>
                    </form>

                    <a href="{{ route('shop.show', $item->product->slug) }}" class="flex flex-col h-full">
                        <div class="relative aspect-[4/5] bg-earth-100 overflow-hidden rounded-t-2xl">
                            <img src="{{ $item->product->image }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            
                            <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300 bg-gradient-to-t from-black/50 to-transparent">
                                <span class="block w-full text-center bg-white text-hiyoucan-900 text-xs font-bold py-2 rounded-lg shadow-lg">View Product</span>
                            </div>
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-xs font-bold text-hiyoucan-600 uppercase mb-1">{{ $item->product->category->name }}</p>
                                <h3 class="font-bold text-gray-900 truncate text-lg group-hover:text-hiyoucan-700 transition">{{ $item->product->name }}</h3>
                            </div>
                            <p class="text-hiyoucan-800 font-bold mt-3 text-lg">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="mt-12">
                {{ $wishlists->links() }}
            </div>
        @else
            <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200" data-aos="zoom-in">
                <div class="text-6xl mb-4">💔</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-8">Save items you love here to check out later.</p>
                <a href="{{ route('shop.index') }}" class="inline-block bg-hiyoucan-700 text-white px-8 py-3 rounded-full font-bold hover:bg-hiyoucan-800 transition shadow-lg transform hover:-translate-y-1">
                    Discover Products
                </a>
            </div>
        @endif
    </div>
</x-public-layout>