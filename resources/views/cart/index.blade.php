<x-public-layout>
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-3xl font-bold text-hiyoucan-900 mb-8" data-aos="fade-right">Shopping Cart</h1>

        @if ($cartItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-12">

                <div class="w-full lg:w-2/3 space-y-6">
                    @foreach ($cartItems as $index => $item)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-6 items-center transition hover:shadow-md"
                            data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">

                            <a href="{{ route('shop.show', $item->product->slug) }}"
                                class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                    class="w-full h-full object-cover hover:scale-110 transition duration-500">
                            </a>

                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">
                                    <a
                                        href="{{ route('shop.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mb-2">{{ $item->product->category->name ?? 'General' }}
                                </p>
                                <p class="text-hiyoucan-700 font-bold">Rp
                                    {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </div>

                            <div class="flex flex-col items-end gap-3">

                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="flex items-center gap-2">
                                        <label class="text-xs text-gray-400">Qty:</label>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}"
                                            min="1" max="{{ $item->product->stock }}"
                                            class="w-16 border-gray-200 rounded-lg text-center text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500"
                                            onchange="this.form.submit()">
                                    </div>
                                </form>

                                <span class="text-xs text-gray-400">
                                    Available: <span class="font-bold text-gray-600">{{ $item->product->stock }}</span>
                                </span>

                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-400 hover:text-red-600 text-sm font-medium transition flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full lg:w-1/3">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-32"
                        data-aos="fade-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-bold">Rp
                                    {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span
                                    class="text-green-600 font-bold text-xs uppercase bg-green-50 px-2 py-1 rounded">Free
                                    Shipping</span>
                            </div>
                            <div
                                class="border-t border-gray-100 pt-4 flex justify-between text-lg font-bold text-hiyoucan-900">
                                <span>Total</span>
                                <span>Rp
                                    {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Shipping Address <span
                                        class="text-red-500">*</span></label>
                                <textarea name="address" rows="3"
                                    class="w-full border-gray-300 rounded-xl text-sm focus:ring-hiyoucan-500 focus:border-hiyoucan-500 shadow-sm placeholder-gray-300"
                                    placeholder="Street, City, Province, Postal Code..." required></textarea>
                            </div>

                            <button type="button" onclick="confirmCheckout(event)"
                                class="w-full bg-hiyoucan-800 text-white py-4 rounded-xl font-bold hover:bg-hiyoucan-900 transition shadow-lg hover:shadow-hiyoucan-500/20 transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                                Checkout Now
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>
                        </form>

                        <div class="mt-4 text-center">
                            <a href="{{ route('shop.index') }}"
                                class="text-sm text-gray-500 hover:text-hiyoucan-700 font-medium">or Continue
                                Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200"
                data-aos="zoom-in">
                <div class="text-6xl mb-4">🛒</div>
                <h3 class="mt-2 text-xl font-bold text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-gray-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-flex items-center px-8 py-4 border border-transparent shadow-lg text-sm font-bold rounded-full text-white bg-hiyoucan-700 hover:bg-hiyoucan-800 transition transform hover:-translate-y-1">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmCheckout(event) {
            event.preventDefault(); // Tahan form agar tidak submit langsung

            // 1. Cek apakah alamat sudah diisi
            const address = document.querySelector('textarea[name="address"]').value;

            if (!address.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Address Required',
                    text: 'Please enter your full shipping address before checkout.',
                    confirmButtonColor: '#4B0600'
                });
                return;
            }

            // 2. Tampilkan Konfirmasi
            Swal.fire({
                title: 'Confirm Order?',
                text: "Are you sure your items and address are correct?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4B0600',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Checkout!',
                cancelButtonText: 'Wait, check again'
            }).then((result) => {
                if (result.isConfirmed) {
                    // 3. Jika user klik Yes, kirim form
                    document.getElementById('checkout-form').submit();
                }
            })
        }
    </script>
</x-public-layout>
