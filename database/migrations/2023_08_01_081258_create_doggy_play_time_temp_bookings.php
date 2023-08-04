<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoggyPlayTimeTempBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doggy_play_time_temp_bookings', function (Blueprint $table) {
            $table->id();
            $table->text('user_id')->nullable();
            $table->text('vendor_id')->nullable();
            $table->text('service_id')->nullable();
            $table->text('total')->nullable();
            $table->text('request_data')->nullable();
            $table->text('payment_id')->nullable();
            $table->text('payment_status')->nullable();
            $table->text('receipt_url')->nullable();
            $table->text('payment_response')->nullable();
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
        Schema::dropIfExists('doggy_play_time_temp_bookings');
    }
}
