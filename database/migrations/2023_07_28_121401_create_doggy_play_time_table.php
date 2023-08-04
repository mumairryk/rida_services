<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoggyPlayTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doggy_play_time_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('service_id');
            $table->timestamp('date');

            $table->string('price')->default('0');
            $table->integer('total_seats')->default(0);
            $table->integer('seats')->default(0);
            $table->integer('guests_booking')->default(0);
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('doggy_play_time_dates');
    }
}
