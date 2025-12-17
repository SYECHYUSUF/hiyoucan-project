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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('payment_method')->default('cod'); // 'cod' atau 'transfer'
        $table->string('payment_proof')->nullable(); // Boleh kosong jika COD
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['payment_method', 'payment_proof']);
    });
}
};
