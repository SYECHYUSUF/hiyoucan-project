<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bagian ini untuk membuat tabel user_addresses (BIARKAN)
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code');
            $table->text('address_detail');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // HAPUS ATAU KOMENTARI BAGIAN DI BAWAH INI (Jika ada):
        /*
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address_snapshot')->nullable();
        });
        */
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};