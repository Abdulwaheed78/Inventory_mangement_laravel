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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sku');
            $table->integer('category_id'); // Using integer instead of unsignedBigInteger
            $table->integer('price');
            $table->integer('stock_quantity');
            $table->integer('warehouse_id'); // Using integer instead of unsignedBigInteger
            $table->string('status');
            $table->text('description')->nullable(); // Adding the column

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
