@if($order->payment_method === 'transfer')
    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 mb-6">
        <h3 class="font-bold text-yellow-800 mb-2">Verifikasi Pembayaran</h3>
        
        @if($order->payment_proof)
            <p class="text-sm text-gray-600 mb-2">Pembeli telah mengupload bukti transfer:</p>
            <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" class="inline-block mb-4">
                <img src="{{ Storage::url($order->payment_proof) }}" class="h-48 rounded shadow-sm border bg-white p-1">
            </a>
            
            <div class="flex gap-2">
                <form action="{{ route('seller.orders.approve_payment', $order->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
                        ✅ Terima Pembayaran & Proses
                    </button>
                </form>

                <form action="{{ route('seller.orders.reject_payment', $order->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700">
                        ❌ Bukti Palsu / Salah
                    </button>
                </form>
            </div>
        @else
            <p class="text-red-600 italic">Bukti pembayaran belum diupload (Error Data).</p>
        @endif
    </div>
@else
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
        <p class="font-bold text-blue-800">Metode: Cash On Delivery (COD)</p>
        <p class="text-sm text-blue-600">Silakan proses pesanan ini dan terima uang tunai saat pengiriman.</p>
    </div>
@endif