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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            // Menggunakan restrict agar user/promo tidak bisa dihapus jika masih ada transaksi terkait
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');

            $table->foreignId('promo_id')
                  ->nullable()
                  ->constrained('promos')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');

            // Transaction Data
            $table->string('invoice_code', 50)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->enum('status', ['pending', 'shipping', 'completed', 'canceled'])->default('pending');
            $table->string('payment_proof')->nullable();

            // Timestamps (created_at & updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};