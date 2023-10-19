<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        $default_provinces = [
            ['name' => 'آذربایجان شرقی'],
            ['name' => 'آذربایجان غربی'],
            ['name' => 'اردبیل'],
            ['name' => 'اصفهان'],
            ['name' => 'البرز'],
            ['name' => 'ایلام'],
            ['name' => 'بوشهر'],
            ['name' => 'تهران'],
            ['name' => 'چهارمحال وبختیاری'],
            ['name' => 'خراسان جنوبی'],
            ['name' => 'خراسان رضوی'],
            ['name' => 'خراسان شمالی'],
            ['name' => 'خوزستان'],
            ['name' => 'زنجان'],
            ['name' => 'سمنان'],
            ['name' => 'سیستان وبلوچستان'],
            ['name' => 'فارس'],
            ['name' => 'قزوین'],
            ['name' => 'قم'],
            ['name' => 'کردستان'],
            ['name' => 'کرمان'],
            ['name' => 'کرمانشاه'],
            ['name' => 'کهگیلویه وبویراحمد'],
            ['name' => 'گلستان'],
            ['name' => 'گیلان'],
            ['name' => 'لرستان'],
            ['name' => 'مازندران'],
            ['name' => 'مرکزی'],
            ['name' => 'هرمزگان'],
            ['name' => 'همدان'],
            ['name' => 'یزد']
        ];
        DB::table('provinces')->insert($default_provinces);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
