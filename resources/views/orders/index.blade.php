<x-public-layout>
    <div class="pt-32 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10" data-aos="fade-down">
            <h1 class="text-3xl font-bold text-hiyoucan-900">My Orders</h1>
            <a href="{{ route('shop.index') }}" class="text-hiyoucan-700 hover:underline text-sm font-medium">Continue Shopping</a>
        </div>

        @if(isset($orders) && $orders->count() > 0)
            <div class="space-y-8">
                @foreach($orders as $index => $order)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition" 
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    
                    <div class="bg-gray-50 px-6 py-5 border-b border-gray-100 flex flex-wrap gap-6 justify-between items-center text-sm">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Order ID</p>
                            <p class="text-gray-900 font-bold font-mono">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Date</p>
                            <p class="text-gray-700">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Shipping To</p>
                            <p class="text-gray-700 truncate" title="{{ $order->address }}">{{ $order->address }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Total</p>
                            <p class="text-hiyoucan-700 font-bold text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white">
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex flex-col sm:flex-row sm:items-center group gap-4">
                                <a href="{{ route('shop.show', $item->product->slug) }}" class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-100 bg-earth-50">
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover object-center group-hover:scale-110 transition duration-500">
                                </a>

                                <div class="flex-1">
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3 class="group-hover:text-hiyoucan-700 transition">
                                            <a href="{{ route('shop.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                        </h3>
                                        <p class="font-bold text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">{{ $item->product->category->name ?? 'General' }}</p>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-sm text-gray-400">Qty: {{ $item->quantity }}</p>
                                        
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                            {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $item->status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $item->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $item->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last) <div class="border-b border-gray-50"></div> @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-32 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200" data-aos="zoom-in">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="mt-2 text-lg font-bold text-gray-900">No orders yet</h3>
                <div class="mt-8">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-lg text-sm font-bold rounded-full text-white bg-hiyoucan-700 hover:bg-hiyoucan-800 transition transform hover:-translate-y-1">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-public-layout>