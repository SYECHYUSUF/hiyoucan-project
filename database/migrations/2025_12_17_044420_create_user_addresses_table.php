<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('user_addresses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('recipient_name'); // Nama Penerima
        $table->string('phone_number');   // No HP Penerima
        
        // Data Wilayah
        $table->string('province');
        $table->string('city');
        $table->string('district'); // Kecamatan
        $table->string('postal_code');
        $table->text('address_detail'); // Jalan, No rumah, RT/RW
        
        $table->boolean('is_primary')->default(false); // Alamat Utama?
        $table->timestamps();
    });

    // Kita juga perlu update tabel orders untuk menyimpan data snapshot alamat & dropship
    Schema::table('orders', function (Blueprint $table) {
        // Hapus kolom address lama yang cuma string biasa (opsional, atau biarkan tertimpa)
        // $table->dropColumn('address'); 
        
        // Tambah kolom detail untuk snapshot (agar kalau user ubah alamat di profil, order lama gak berubah)
        $table->text('shipping_address_snapshot')->nullable(); // Simpan JSON alamat lengkap
        
        // Fitur Dropship
        $table->boolean('is_dropship')->default(false);
        $table->string('dropship_name')->nullable();
        $table->string('dropship_phone')->nullable();
    });
}
};
