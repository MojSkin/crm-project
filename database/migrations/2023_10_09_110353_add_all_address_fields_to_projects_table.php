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
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id')->after('description');
            $table->string('region', 30)->after('city_id');
            $table->string('main_street', 100)->after('region');
            $table->string('aux1', 100)->nullable()->after('main_street');
            $table->string('aux2', 100)->nullable()->after('aux1');
            $table->string('alley1', 100)->nullable()->after('aux2');
            $table->string('alley2', 250)->nullable()->after('alley1');
            $table->string('address', 250)->after('alley2');
            $table->string('lat', 30)->nullable()->after('address');
            $table->string('long', 30)->nullable()->after('lat');
            $table->string('po_code', 10)->nullable()->after('long');
            $table->smallInteger('blocks')->default(1)->after('po_code');
            $table->smallInteger('units')->default(1)->after('blocks');
            $table->tinyInteger('floors')->default(1)->after('units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('city_id');
            $table->dropColumn('region');
            $table->dropColumn('main_street');
            $table->dropColumn('aux1');
            $table->dropColumn('aux2');
            $table->dropColumn('alley1');
            $table->dropColumn('alley2');
            $table->dropColumn('address');
            $table->dropColumn('lat');
            $table->dropColumn('long');
            $table->dropColumn('po_code');
            $table->dropColumn('blocks');
            $table->dropColumn('units');
            $table->dropColumn('floors');
        });
    }
};
