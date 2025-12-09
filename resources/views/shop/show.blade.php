<x-public-layout>
    <div class="pt-32 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-100" data-aos="fade-down">
                {{ session('error') }}</div>
        @endif

        <nav class="text-sm mb-8 text-gray-500" data-aos="fade-right">
            <a href="/" class="hover:text-hiyoucan-700 transition">Home</a> <span class="mx-2">/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-hiyoucan-700 transition">Shop</a> <span
                class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
            <div class="bg-earth-100 rounded-3xl overflow-hidden aspect-square shadow-sm" data-aos="fade-right">
                <img src="{{ $product->image }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover hover:scale-105 transition duration-700">
            </div>

            <div data-aos="fade-left">
                <h1 class="text-4xl md:text-5xl font-bold text-hiyoucan-900 mb-4">{{ $product->name }}</h1>

                <div class="flex items-center space-x-4 mb-8">
                    <p class="text-3xl text-hiyoucan-700 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center text-yellow-400 text-sm bg-yellow-50 px-3 py-1 rounded-full">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-gray-900 font-bold ml-1">{{ $product->average_rating }}</span>
                        <span class="text-gray-500 ml-1">({{ $product->reviews->count() }} Reviews)</span>
                    </div>
                </div>

                <p class="text-gray-600 leading-relaxed mb-8 text-lg">
                    {{ $product->description }}
                </p>

                @if (Auth::check() && Auth::user()->role !== 'user' && Auth::user()->role !== 'buyer')
                    <div class="bg-gray-100 p-4 rounded-xl text-gray-600 text-sm mb-6 border border-gray-200">
                        Login as <strong>Buyer</strong> to purchase this item.
                    </div>
                @else
                    @if ($product->stock > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="mb-10">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex flex-col sm:flex-row items-end gap-6">
                                <div class="w-full sm:w-32">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Quantity</label>
                                        <span
                                            class="text-xs font-medium text-hiyoucan-600 bg-hiyoucan-50 px-2 rounded-full">Stok:
                                            {{ $product->stock }}</span>
                                    </div>
                                    <input type="number" name="quantity" value="1" min="1"
                                        max="{{ $product->stock }}"
                                        class="w-full border-gray-300 rounded-xl text-center py-3 font-bold text-lg"
                                        oninput="if(this.value > {{ $product->stock }}) this.value = {{ $product->stock }};">
                                </div>
                                <button type="submit"
                                    class="flex-1 w-full bg-hiyoucan-800 text-white px-8 py-3.5 rounded-xl font-bold text-lg hover:bg-hiyoucan-900 transition flex items-center justify-center gap-3">
                                    Add to Cart
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mb-10">
                            <button disabled
                                class="w-full bg-gray-200 text-gray-400 px-8 py-4 rounded-xl font-bold text-lg cursor-not-allowed border border-gray-300">Sold
                                Out</button>
                        </div>
                    @endif

                @endif

                <div class="pt-8 border-t border-gray-100 text-sm text-gray-500 space-y-3">
                    <p class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span>Category</span>
                        <span
                            class="font-bold text-hiyoucan-700">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span>
                    </p>
                    <p class="flex justify-between border-b border-dashed border-gray-200 pb-2">
                        <span>Stock</span>
                        <span class="font-bold text-gray-900">{{ $product->stock }} items</span>
                    </p>
                    <p class="flex justify-between">
                        <span>Seller</span>
                        <span class="font-bold text-gray-900">{{ $product->seller->name }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-12 mb-20" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-hiyoucan-900 mb-8">Customer Reviews</h2>

            @auth
                @if (Auth::user()->role === 'user' || Auth::user()->role === 'buyer')
                    <div class="bg-gray-50 p-6 rounded-2xl mb-10 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-2">Write a Review</h3>
                        <p class="text-xs text-gray-500 mb-4">Share your experience (Must have purchased & completed order).
                        </p>

                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <select name="rating"
                                    class="border-gray-200 rounded-lg text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500">
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Good)</option>
                                    <option value="3">⭐⭐⭐ (Average)</option>
                                    <option value="2">⭐⭐ (Poor)</option>
                                    <option value="1">⭐ (Bad)</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                                <textarea name="comment" rows="3"
                                    class="w-full border-gray-200 rounded-xl text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500"
                                    placeholder="How was the product?"></textarea>
                            </div>
                            <button type="submit"
                                class="bg-hiyoucan-700 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-hiyoucan-800 transition">Submit
                                Review</button>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="space-y-8">
                @forelse($product->reviews as $review)
                    <div class="border-b border-gray-100 pb-8 last:border-0 last:pb-0">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-10 w-10 rounded-full bg-hiyoucan-100 flex items-center justify-center text-hiyoucan-700 font-bold text-sm">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex text-yellow-400 text-sm">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                                @for ($i = $review->rating; $i < 5; $i++)
                                    <span class="text-gray-200">★</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed pl-14">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-400 italic">No reviews yet. Be the first to review!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div data-aos="fade-up" data-aos-delay="100">
            <h3 class="text-2xl font-bold text-hiyoucan-900 mb-8">You May Also Like</h3>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('shop.show', $related->slug) }}"
                        class="group block bg-white rounded-2xl p-4 shadow-sm hover:shadow-lg transition border border-gray-100">
                        <div class="aspect-square bg-earth-100 rounded-xl overflow-hidden mb-4">
                            <img src="{{ $related->image }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <h4 class="font-bold text-gray-900 truncate group-hover:text-hiyoucan-700 transition">
                            {{ $related->name }}</h4>
                        <p class="text-hiyoucan-700 text-sm font-medium mt-1">Rp
                            {{ number_format($related->price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-public-layout>
