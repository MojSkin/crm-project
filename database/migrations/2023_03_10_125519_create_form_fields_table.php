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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('title', 100);
            $table->string('hint', 250)->nullable();
            $table->string('description', 250)->nullable();
            $table->string('placeholder')->nullable();
            $table->tinyInteger('type')->default(1);
            $table->string('default', 250)->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_printable')->default(true);
            $table->string('min', 50)->nullable();
            $table->string('max', 50)->nullable();
            $table->tinyInteger('max_rows')->nullable();
            $table->integer('steps')->nullable();
            $table->mediumText('options')->nullable();
            $table->tinyText('validator')->nullable();
            $table->tinyInteger('tab_order')->default(0);
            $table->tinyInteger('col_mobile')->default(12);
            $table->tinyInteger('col_tablet')->default(12);
            $table->tinyInteger('col_desktop')->default(12);
            $table->tinyInteger('col_widescreen')->default(12);
            $table->string('custom_id')->nullable();
            $table->string('custom_class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
