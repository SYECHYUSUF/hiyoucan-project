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
                                <th class="p-4 font-bold text-gray-600">Date</th>
                                <th class="p-4 font-bold text-gray-600">Order ID</th>
                                <th class="p-4 font-bold text-gray-600">Customer</th>
                                <th class="p-4 font-bold text-gray-600">Product</th>
                                <th class="p-4 font-bold text-gray-600">Qty</th>
                                <th class="p-4 font-bold text-gray-600">Status</th>
                                <th class="p-4 font-bold text-gray-600 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orderItems as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-gray-500">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="p-4 font-mono font-bold text-gray-900">#ORD-{{ str_pad($item->order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-4">
                                    <span class="font-bold block text-gray-900">{{ $item->order->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $item->order->address }}</span>
                                </td>

                                <td class="p-4 flex items-center gap-3">
                                    <img src="{{ $item->product->image }}" class="w-10 h-10 rounded object-cover border border-gray-200">
                                    <span class="font-medium text-gray-700">{{ $item->product->name }}</span>
                                </td>
                                <td class="p-4 font-bold text-center">{{ $item->quantity }}</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                        {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $item->status == 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $item->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $item->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('seller.orders.update-status', $item->id) }}" method="POST" class="flex justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <input type="hidden" name="status" value="">

                                        @if($item->status == 'pending')
                                            <button type="button" 
                                                    onclick="confirmStatusUpdate(this, 'processing', 'Accept Order', 'Are you sure you want to process this order?')"
                                                    class="bg-blue-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-blue-700 text-xs font-bold transition transform hover:-translate-y-0.5">
                                                Accept
                                            </button>
                                            
                                            <button type="button" 
                                                    onclick="confirmStatusUpdate(this, 'cancelled', 'Reject Order', 'Are you sure you want to reject this order? This cannot be undone.')"
                                                    class="bg-red-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-red-700 text-xs font-bold transition transform hover:-translate-y-0.5">
                                                Reject
                                            </button>
                                        
                                        @elseif($item->status == 'processing')
                                            <button type="button" 
                                                    onclick="confirmStatusUpdate(this, 'completed', 'Complete Order', 'Is the item delivered correctly?')"
                                                    class="bg-green-600 text-white px-3 py-1.5 rounded shadow-sm hover:bg-green-700 text-xs font-bold transition transform hover:-translate-y-0.5">
                                                Mark as Done
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs italic">No action needed</span>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $orderItems->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function confirmStatusUpdate(button, statusValue, titleText, bodyText) {
            // 1. Cari Form induk dari tombol yang diklik
            const form = button.closest('form');
            
            // 2. Cari Input Hidden "status" di dalam form tersebut
            const statusInput = form.querySelector('input[name="status"]');

            // 3. Tampilkan Popup Konfirmasi
            Swal.fire({
                title: titleText,
                text: bodyText,
                icon: statusValue === 'cancelled' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: statusValue === 'cancelled' ? '#d33' : '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // 4. Isi input hidden dengan status yang dipilih
                    statusInput.value = statusValue;
                    
                    // 5. Kirim Form
                    form.submit();
                }
            });
        }
    </script>

</x-dashboard-layout>