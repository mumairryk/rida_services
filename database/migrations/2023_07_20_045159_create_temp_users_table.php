<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone')->unique();
            $table->integer('phone_verified')->default(0);
            $table->integer('active')->default(0);
            $table->integer('email_verified')->default(0);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('role')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_image')->nullable();
            $table->string('user_phone_otp')->nullable();
            $table->string('user_email_otp')->nullable();
            $table->string('user_device_token')->nullable();
            $table->string('user_device_type')->nullable();
            $table->string('user_access_token')->nullable();
            $table->string('firebase_user_key')->nullable();
            $table->string('device_cart_id')->nullable();

            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_users');
    }
}
