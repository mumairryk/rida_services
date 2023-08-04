<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroomerCalendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groomer_calenders', function (Blueprint $table) {
            $table->id();
            $table->integer('groomer_id');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
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
        Schema::dropIfExists('groomer_calenders');
    }
}
