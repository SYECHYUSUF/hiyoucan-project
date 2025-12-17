<x-dashboard-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incoming Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6 border border-green-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="p-4 font-bold text-gray-600">Order ID</th>
                                <th class="p-4 font-bold text-gray-600">Customer & Addr</th>
                                <th class="p-4 font-bold text-gray-600">Payment Info</th>
                                <th class="p-4 font-bold text-gray-600">Product</th>
                                <th class="p-4 font-bold text-gray-600">Status</th>
                                <th class="p-4 font-bold text-gray-600 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orderItems as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 font-mono font-bold text-gray-900">
                                    #{{ str_pad($item->order->id, 5, '0', STR_PAD_LEFT) }}
                                    <div class="text-xs text-gray-400 mt-1">{{ $item->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="font-bold block text-gray-900">{{ $item->order->user->name }}</span>
                                    <div class="text-xs text-gray-500 mt-1 max-w-[200px] truncate" title="{{ $item->order->shipping_address_snapshot }}">
                                        {{ Str::limit($item->order->shipping_address_snapshot, 50) }}
                                    </div>
                                </td>

                                <td class="p-4">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-bold uppercase mb-1 border
                                        {{ $item->order->payment_method == 'cod' ? 'bg-gray-100 border-gray-300' : 'bg-blue-50 border-blue-200 text-blue-700' }}">
                                        {{ $item->order->payment_method }}
                                    </span>

                                    @if($item->order->payment_method != 'cod')
                                        @if($item->order->payment_proof)
                                            <a href="{{ asset('storage/' . $item->order->payment_proof) }}" target="_blank" 
                                               class="flex items-center gap-1 text-xs text-blue-600 font-bold hover:underline mt-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="block text-xs text-red-500 italic mt-1">Belum Upload</span>
                                        @endif
                                    @else
                                        <div class="text-xs text-gray-400 italic mt-1">Bayar ditempat</div>
                                    @endif
                                </td>

                                <td class="p-4 flex items-center gap-3">
                                    <img src="{{ $item->product->image }}" class="w-10 h-10 rounded object-cover border border-gray-200">
                                    <div>
                                        <span class="font-medium text-gray-700 block">{{ $item->product->name }}</span>
                                        <span class="text-xs text-gray-500">Qty: {{ $item->quantity }}</span>
                                    </div>
                                </td>
                                
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                        {{ $item->status == 'pending' || $item->status == 'pending_payment' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $item->status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $item->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $item->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($item->status)) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('seller.orders.update-status', $item->id) }}" method="POST" class="flex justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="">

                                        @if($item->status == 'pending' || $item->status == 'pending_payment')
                                            <button type="button" onclick="confirmStatusUpdate(this, 'processing', 'Accept Order', 'Pastikan bukti pembayaran valid (jika ada).')"
                                                    class="bg-blue-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-blue-700 text-xs font-bold">
                                                Accept
                                            </button>
                                            <button type="button" onclick="confirmStatusUpdate(this, 'cancelled', 'Reject Order', 'Yakin tolak pesanan ini?')"
                                                    class="bg-red-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-red-700 text-xs font-bold">
                                                Reject
                                            </button>
                                        @elseif($item->status == 'processing')
                                            <button type="button" onclick="confirmStatusUpdate(this, 'completed', 'Complete Order', 'Barang sudah diterima pembeli?')"
                                                    class="bg-green-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-green-700 text-xs font-bold">
                                                Mark Done
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $orderItems->links() }}</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmStatusUpdate(button, statusValue, titleText, bodyText) {
            const form = button.closest('form');
            const statusInput = form.querySelector('input[name="status"]');
            Swal.fire({
                title: titleText,
                text: bodyText,
                icon: statusValue === 'cancelled' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: statusValue === 'cancelled' ? '#d33' : '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    statusInput.value = statusValue;
                    form.submit();
                }
            });
        }
    </script>
</x-dashboard-layout>