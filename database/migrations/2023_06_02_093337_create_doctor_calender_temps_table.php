<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorCalenderTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_calender_temps', function (Blueprint $table) {
            $table->id();
            $table->integer('doctor_id')->default(0);
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
        Schema::dropIfExists('doctor_calender_temps');
    }
}
