<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Jika alamat dihapus, riwayat pesanan tetap ada
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number', 40)->unique();
            $table->string('status', 20)->default('pending'); // pending|paid|shipped|completed|cancelled
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
