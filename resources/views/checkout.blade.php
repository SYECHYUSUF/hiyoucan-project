<x-public-layout>
    <div class="bg-gray-50 min-h-screen pt-32 pb-24" 
         x-data="{ 
            // 1. Logika Toast & Notifikasi
            showToast: {{ session('success') ? 'true' : 'false' }},
            toastTitle: '{{ session('success') ? 'Berhasil!' : 'Memproses Pesanan...' }}',
            toastDesc: '{{ session('success') ?? 'Mohon tunggu sebentar...' }}',
            
            // 2. Logika Keuangan
            subtotal: {{ $total }}, 
            shippingCost: 0,
            selectedCourier: '',
            
            // 3. Logika Pembayaran Baru
            paymentMethod: 'cod', // Default
            selectedBank: 'bca',
            
            // Helper: Format Rupiah
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            },
            
            // Helper: Generate VA Dummy (Berdasarkan bank + no hp user)
            getVirtualAccount() {
                const mapCode = { 'bca': '88000', 'bri': '12345', 'bni': '98888', 'mandiri': '70000' };
                // Anggap nomor HP user diambil dari input alamat atau auth user (disini simulasi)
                return (mapCode[this.selectedBank] || '00000') + '081234567890'; 
            },

            get grandTotal() {
                return this.subtotal + this.shippingCost;
            }
         }"
         x-init="if(showToast) setTimeout(() => showToast = false, 5000)">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center"><a href="/" class="text-sm font-medium text-gray-500 hover:text-hiyoucan-700">Home</a></li>
                    <li><div class="flex items-center"><span class="mx-2 text-gray-400">/</span><a href="{{ route('cart.index') }}" class="text-sm font-medium text-gray-500 hover:text-hiyoucan-700">Keranjang</a></div></li>
                    <li aria-current="page"><div class="flex items-center"><span class="mx-2 text-gray-400">/</span><span class="text-sm font-bold text-hiyoucan-700">Checkout</span></div></li>
                </ol>
            </nav>

            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('cart.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white shadow-sm text-gray-400 hover:text-hiyoucan-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Checkout</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
                
                <div class="lg:col-span-8 space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
                         x-data="{ mode: '{{ $addresses->count() > 0 ? 'select' : 'new' }}' }">
                        
                        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-hiyoucan-100 flex items-center justify-center text-hiyoucan-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900">Alamat Pengiriman</h2>
                            </div>
                            
                            <button type="button" @click="mode = (mode === 'select' ? 'new' : 'select')" 
                                    class="text-sm font-bold text-hiyoucan-700 hover:bg-hiyoucan-50 px-4 py-2 rounded-lg transition flex items-center gap-2">
                                <span x-text="mode === 'select' ? 'Tambah Alamat Baru' : 'Pilih Alamat Tersimpan'"></span>
                                <svg x-show="mode === 'select'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>

                        <div x-show="mode === 'new'" x-transition class="p-6 bg-gray-50/50">
                            <form action="{{ route('address.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Penerima</label>
                                        <input type="text" name="recipient_name" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11" placeholder="Nama Lengkap">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">No. Telepon</label>
                                        <input type="number" name="phone_number" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11" placeholder="08xxxxxxxx">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5" x-data="regionData()">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Provinsi</label>
                                        <select name="province" x-model="selectedProv" @change="updateCities()" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11">
                                            <option value="">Pilih Provinsi</option>
                                            <template x-for="prov in provinces" :key="prov"><option :value="prov" x-text="prov"></option></template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kota</label>
                                        <select name="city" x-model="selectedCity" :disabled="!selectedProv" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 disabled:bg-gray-100">
                                            <option value="">Pilih Kota</option>
                                            <template x-for="city in cities" :key="city"><option :value="city" x-text="city"></option></template>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kecamatan</label>
                                        <input type="text" name="district" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kode Pos</label>
                                        <input type="number" name="postal_code" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Detail Alamat</label>
                                    <textarea name="address_detail" rows="3" required class="w-full rounded-xl border-gray-200 text-sm shadow-sm" placeholder="Jalan, No Rumah, RT/RW..."></textarea>
                                </div>

                                <div class="flex justify-end border-t border-gray-200 pt-5">
                                    <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-black transition shadow-lg">Simpan Alamat</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <form id="checkout-process-form" action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div x-show="mode === 'select'" class="space-y-4" x-data="{ mode: '{{ $addresses->count() > 0 ? 'select' : 'new' }}' }">
                             @if($addresses->count() > 0)
                                <div class="grid gap-4">
                                @foreach($addresses as $address)
                                <div class="relative group block">
                                    <input type="radio" name="selected_address_id" id="addr_{{ $address->id }}" value="{{ $address->id }}" 
                                           class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    
                                    <label for="addr_{{ $address->id }}" class="cursor-pointer block p-5 rounded-2xl border-2 border-gray-100 bg-white hover:border-hiyoucan-300 transition-all peer-checked:border-hiyoucan-600 peer-checked:bg-hiyoucan-50/50 shadow-sm peer-checked:shadow-md relative">
                                        <div class="flex justify-between items-start mb-2 pr-16">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 text-lg">{{ $address->recipient_name }}</span>
                                                @if($loop->first)<span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Utama</span>@endif
                                            </div>
                                            <div class="h-6 w-6 rounded-full border-2 border-gray-300 peer-checked:border-hiyoucan-600 peer-checked:bg-hiyoucan-600 flex items-center justify-center">
                                                <div class="h-2.5 w-2.5 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ $address->phone_number }}</p>
                                        <p class="text-gray-500 text-sm mt-1">{{ $address->address_detail }}, {{ $address->city }}, {{ $address->province }}</p>
                                    </label>

                                    <div class="absolute top-5 right-5 flex gap-2 z-10">
                                        <a href="{{ route('address.edit', $address->id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition" title="Edit Alamat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        
                                        <button type="button" onclick="if(confirm('Yakin ingin menghapus alamat ini?')) document.getElementById('delete-addr-{{ $address->id }}').submit();" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-full transition" title="Hapus Alamat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                            @else
                                <div class="bg-white p-8 rounded-2xl border-2 border-dashed border-gray-300 text-center">
                                    <p class="text-gray-500 font-medium">Belum ada alamat tersimpan.</p>
                                </div>
                            @endif
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900">Pilih Pengiriman</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="shipping_service" value="jnt" 
                                           @click="shippingCost = 18000; selectedCourier = 'J&T Express'" 
                                           class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-red-500 hover:bg-red-50 peer-checked:border-red-600 peer-checked:bg-red-50 transition-all text-center h-full flex flex-col justify-center">
                                        <span class="font-bold text-gray-800 block mb-1">J&T Express</span>
                                        <span class="text-sm text-gray-500">Reguler (2-3 Hari)</span>
                                        <span class="text-red-600 font-bold mt-2">Rp 18.000</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="shipping_service" value="jne" 
                                           @click="shippingCost = 20000; selectedCourier = 'JNE Oke'" 
                                           class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all text-center h-full flex flex-col justify-center">
                                        <span class="font-bold text-gray-800 block mb-1">JNE</span>
                                        <span class="text-sm text-gray-500">OKE (3-5 Hari)</span>
                                        <span class="text-blue-600 font-bold mt-2">Rp 20.000</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="shipping_service" value="spx" 
                                           @click="shippingCost = 15000; selectedCourier = 'Shopee Express'" 
                                           class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-orange-500 hover:bg-orange-50 peer-checked:border-orange-600 peer-checked:bg-orange-50 transition-all text-center h-full flex flex-col justify-center">
                                        <span class="font-bold text-gray-800 block mb-1">Shopee Express</span>
                                        <span class="text-sm text-gray-500">Hemat (3-7 Hari)</span>
                                        <span class="text-orange-600 font-bold mt-2">Rp 15.000</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <h2 class="text-lg font-bold text-gray-900">Metode Pembayaran</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-gray-400 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-all text-center h-full flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        <span class="font-bold text-sm">QRIS</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="transfer" x-model="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-600 peer-checked:text-white transition-all text-center h-full flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span class="font-bold text-sm">Transfer Bank</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-green-400 peer-checked:border-green-600 peer-checked:bg-green-600 peer-checked:text-white transition-all text-center h-full flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span class="font-bold text-sm">COD</span>
                                    </div>
                                </label>
                            </div>

                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                
                                <div x-show="paymentMethod === 'qris'" x-transition class="text-center">
                                    <h3 class="font-bold text-gray-900 mb-2">Scan QRIS untuk Membayar</h3>
                                    <p class="text-sm text-gray-500 mb-4">Silakan scan kode di bawah ini menggunakan e-wallet Anda.</p>
                                    
                                    <div class="bg-white p-4 inline-block rounded-lg shadow-sm border mb-4">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=ExampleQRISCode" alt="QRIS Barcode" class="w-32 h-32">
                                    </div>
                                    
                                    <div class="text-left mt-4">
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Upload Bukti Pembayaran <span class="text-red-500">* (Wajib)</span></label>
                                        <input type="file" name="payment_proof_qris" accept="image/*" 
                                               :required="paymentMethod === 'qris'"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-900 file:text-white hover:file:bg-gray-700 cursor-pointer">
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                                    </div>
                                </div>

                                <div x-show="paymentMethod === 'transfer'" x-transition>
                                    <h3 class="font-bold text-gray-900 mb-4 text-center">Pilih Bank Tujuan</h3>
                                    
                                    <div class="flex flex-wrap justify-center gap-3 mb-6">
                                        <template x-for="bank in ['bca', 'bri', 'bni', 'mandiri']">
                                            <button type="button" 
                                                    @click="selectedBank = bank"
                                                    :class="selectedBank === bank ? 'ring-2 ring-blue-600 bg-blue-50' : 'bg-white border-gray-200 hover:bg-gray-50'"
                                                    class="px-4 py-2 rounded-lg border font-bold uppercase text-sm transition" 
                                                    x-text="bank">
                                            </button>
                                        </template>
                                    </div>

                                    <div class="bg-white border border-blue-100 rounded-lg p-4 mb-4 text-center">
                                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Nomor Virtual Account (<span x-text="selectedBank" class="uppercase"></span>)</p>
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-2xl font-mono font-bold text-blue-700 tracking-wider" x-text="getVirtualAccount()"></span>
                                            <button type="button" @click="navigator.clipboard.writeText(getVirtualAccount()); alert('Disalin!')" class="text-gray-400 hover:text-blue-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>
                                        <p class="text-xs text-orange-500 mt-2">Dicek otomatis. Transfer sesuai nominal hingga 3 digit terakhir.</p>
                                    </div>

                                    <div class="text-left mt-4 border-t border-gray-200 pt-4">
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Upload Bukti Transfer <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                        <input type="file" name="payment_proof_transfer" accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                    </div>
                                </div>

                                <div x-show="paymentMethod === 'cod'" x-transition class="text-center py-4">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Bayar di Tempat (COD)</h3>
                                    <p class="text-sm text-gray-500 mt-2 max-w-xs mx-auto">Anda akan membayar tunai kepada kurir saat paket sampai di alamat tujuan.</p>
                                    <p class="text-xs text-red-500 mt-2 italic font-medium">Pastikan ada orang di rumah untuk menerima paket.</p>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 sticky top-28 overflow-hidden">
                        <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900">Ringkasan Pesanan</h2>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($cartItems as $item)
                                <div class="flex gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-100">
                                        <img src="{{ $item->product->image }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $item->product->name }}</h4>
                                        <div class="flex justify-between mt-1">
                                            <span class="text-xs text-gray-500">{{ $item->quantity }}x</span>
                                            <span class="text-sm font-bold text-hiyoucan-700">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="border-t border-dashed border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal Produk</span>
                                    <span class="font-medium" x-text="formatRupiah(subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Biaya Layanan</span>
                                    <span class="font-medium">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Ongkos Kirim <span x-show="selectedCourier" x-text="'(' + selectedCourier + ')'" class="text-xs text-hiyoucan-600 font-bold"></span></span>
                                    <span class="font-medium" :class="shippingCost > 0 ? 'text-gray-900' : 'text-gray-400'" 
                                          x-text="shippingCost > 0 ? formatRupiah(shippingCost) : '-'"></span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex justify-between items-end">
                                    <span class="text-sm font-bold text-gray-500">Total Tagihan</span>
                                    <span class="text-2xl font-extrabold text-hiyoucan-700" x-text="formatRupiah(grandTotal)"></span>
                                </div>
                            </div>

                            <button type="submit" form="checkout-process-form"
                                    :disabled="shippingCost === 0"
                                    :class="shippingCost === 0 ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-hiyoucan-700 hover:bg-hiyoucan-800 shadow-xl shadow-hiyoucan-500/20'"
                                    @click="toastTitle = 'Memproses Pesanan...'; toastDesc = 'Mohon tunggu sebentar...'; showToast = true"
                                    class="w-full text-white py-4 rounded-xl font-bold text-base transition-all flex justify-center items-center gap-2 group">
                                <span x-text="shippingCost === 0 ? 'Pilih Pengiriman Dulu' : 'Buat Pesanan Sekarang'"></span>
                                <svg x-show="shippingCost > 0" class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            
                            <div class="flex items-center justify-center gap-2 text-gray-400 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span class="text-[10px] font-medium">Transaksi Aman & Terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <div x-show="showToast" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-50 w-max" style="display: none;">
            
            <div class="bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white shrink-0 animate-pulse">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm" x-text="toastTitle"></h4>
                    <p class="text-xs text-gray-300 mt-0.5" x-text="toastDesc"></p>
                </div>
            </div>
        </div>

        @foreach($addresses as $address)
            <form id="delete-addr-{{ $address->id }}" action="{{ route('address.destroy', $address->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

    </div>

    <script>
        function regionData() {
            return {
                selectedProv: '',
                selectedCity: '',
                provinces: [
                    'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Sulawesi Selatan', 'Sumatera Utara'
                ],
                cities: [],
                dataMap: {
                    'DKI Jakarta': ['Jakarta Selatan', 'Jakarta Pusat', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'],
                    'Jawa Barat': ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cimahi', 'Sukabumi', 'Tasikmalaya'],
                    'Jawa Tengah': ['Semarang', 'Surakarta (Solo)', 'Magelang', 'Pekalongan', 'Salatiga'],
                    'DI Yogyakarta': ['Yogyakarta', 'Sleman', 'Bantul', 'Gunung Kidul', 'Kulon Progo'],
                    'Jawa Timur': ['Surabaya', 'Malang', 'Sidoarjo', 'Kediri', 'Blitar', 'Madiun'],
                    'Banten': ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon'],
                    'Bali': ['Denpasar', 'Badung', 'Gianyar', 'Tabanan'],
                    'Sulawesi Selatan': ['Makassar', 'Gowa', 'Maros', 'Parepare'],
                    'Sumatera Utara': ['Medan', 'Binjai', 'Pematang Siantar', 'Deli Serdang']
                },
                updateCities() {
                    this.cities = this.dataMap[this.selectedProv] || [];
                    this.selectedCity = '';
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</x-public-layout>