<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroomerCalenderTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groomer_calender_temps', function (Blueprint $table) {
            $table->id();
            $table->integer('groomer_id')->default(0);
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string("device_id");
            $table->string("event_title")->nullable();
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
        Schema::dropIfExists('groomer_calender_temps');
    }
}
