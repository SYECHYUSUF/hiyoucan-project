<x-public-layout>
    <div class="bg-gray-50 min-h-screen pt-32 pb-24">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('checkout.index') }}" class="p-2 rounded-full bg-white shadow-sm text-gray-500 hover:text-hiyoucan-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-2xl font-extrabold text-gray-900">Edit Alamat Pengiriman</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                
                <form action="{{ route('address.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Penerima</label>
                            <input type="text" name="recipient_name" 
                                   value="{{ old('recipient_name', $address->recipient_name) }}" 
                                   required 
                                   class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">No. Telepon</label>
                            <input type="number" name="phone_number" 
                                   value="{{ old('phone_number', $address->phone_number) }}" 
                                   required 
                                   class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5" 
                         x-data="regionData()"
                         x-init="initData('{{ $address->province }}', '{{ $address->city }}')">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Provinsi</label>
                            <select name="province" x-model="selectedProv" @change="updateCities()" required 
                                    class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                                <option value="">Pilih Provinsi</option>
                                <template x-for="prov in provinces" :key="prov">
                                    <option :value="prov" x-text="prov" :selected="prov === selectedProv"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kota</label>
                            <select name="city" x-model="selectedCity" :disabled="!selectedProv" required 
                                    class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 disabled:bg-gray-100 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                                <option value="">Pilih Kota</option>
                                <template x-for="city in cities" :key="city">
                                    <option :value="city" x-text="city" :selected="city === selectedCity"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kecamatan</label>
                            <input type="text" name="district" 
                                   value="{{ old('district', $address->district) }}"
                                   required 
                                   class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kode Pos</label>
                            <input type="number" name="postal_code" 
                                   value="{{ old('postal_code', $address->postal_code) }}"
                                   required 
                                   class="w-full rounded-xl border-gray-200 text-sm shadow-sm h-11 focus:border-hiyoucan-500 focus:ring-hiyoucan-500">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Detail Alamat</label>
                        <textarea name="address_detail" rows="4" required 
                                  class="w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-hiyoucan-500 focus:ring-hiyoucan-500" 
                                  placeholder="Jalan, No Rumah, RT/RW...">{{ old('address_detail', $address->address_detail) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('checkout.index') }}" class="px-6 py-3 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-black transition shadow-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                // Fungsi untuk mengisi data awal saat mode Edit
                initData(savedProv, savedCity) {
                    this.selectedProv = savedProv;
                    this.cities = this.dataMap[savedProv] || [];
                    
                    // Tunggu sebentar agar dropdown terisi, baru set kota
                    this.$nextTick(() => {
                        this.selectedCity = savedCity;
                    });
                },
                updateCities() {
                    this.cities = this.dataMap[this.selectedProv] || [];
                    this.selectedCity = '';
                }
            }
        }
    </script>
</x-public-layout>