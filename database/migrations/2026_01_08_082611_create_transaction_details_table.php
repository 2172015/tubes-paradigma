<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable();
            $table->decimal('price_at_purchase', 14, 2)->nullable();
            
            // Foreign Keys
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('restrict')->onUpdate('restrict');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict')->onUpdate('restrict');
            
            // Tabel ini biasanya tidak butuh timestamps, tapi kalau mau nambah tinggal $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
