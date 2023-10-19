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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('prefix')->default(0);
            $table->string('fName', 100);
            $table->string('lName', 100)->nullable();
            $table->string('nickName', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('organization', 100)->nullable();
            $table->string('fileName', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
