<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

// database/migrations/xxxx_xx_xx_create_orders_table.php

public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('total_price', 12, 2);
        $table->string('status')->default('pending'); // Kolom status yang sempat hilang
        $table->text('shipping_address_snapshot');
        $table->string('shipping_service')->nullable(); // Tambahkan jika perlu
        $table->decimal('shipping_cost', 12, 2)->default(0);
        $table->string('payment_method')->default('cod');
        $table->string('payment_proof')->nullable();
        $table->string('address')->nullable(); // Untuk report singkat kota
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};