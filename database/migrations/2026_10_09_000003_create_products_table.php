<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Kategori tidak boleh dihapus selama masih punya produk
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('sku', 30)->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');          // Rupiah (tanpa desimal)
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
