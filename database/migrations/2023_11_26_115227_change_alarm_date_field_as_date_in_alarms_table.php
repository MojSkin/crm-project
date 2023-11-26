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
        Schema::table('alarms', function (Blueprint $table) {
            $table->unsignedBigInteger('alarmable_id')->nullable()->change();
            $table->string('alarmable_type')->nullable()->change();
            $table->date('alarm_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alarms', function (Blueprint $table) {
            $table->datetime('alarm_date')->change();
            $table->unsignedBigInteger('alarmable_id')->change();
            $table->string('alarmable_type')->change();
        });
    }
};
