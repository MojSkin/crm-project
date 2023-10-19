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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile', 10)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->unsignedBigInteger('user_role_id')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'id' => 2914,
            'username' => 'mojskin',
            'email' => 'mojtaba.meskin@gmail.com',
            'mobile' => '9177378304',
            'email_verified_at' => \Carbon\Carbon::now(),
            'mobile_verified_at' => \Carbon\Carbon::now(),
            'password' => \Illuminate\Support\Facades\Hash::make('P@ssw0rd')
        ]);

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users AUTO_INCREMENT = 104001;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
