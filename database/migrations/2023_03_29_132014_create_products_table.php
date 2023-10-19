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
            $table->string('title');
            $table->string('slug');
            $table->tinyText('short_description');
            $table->longText('description');
            $table->tinyInteger('max_per_order')->default(0);
            $table->tinyInteger('max_per_day')->default(0);
            $table->tinyInteger('min_stock')->default(0);
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_unit_id');
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
