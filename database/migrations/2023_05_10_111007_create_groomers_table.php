<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groomers', function (Blueprint $table) {
            $table->id();
            $table->string('name',900);

            $table->integer('sunday')->default(0);
            $table->string('sun_from')->nullable();
            $table->string('sun_to')->nullable();
            
            $table->integer('monday')->default(0);
            $table->string('mon_from')->nullable();
            $table->string('mon_to')->nullable();

            $table->integer('tuesday')->default(0);
            $table->string('tues_from')->nullable();
            $table->string('tues_to')->nullable();


            $table->integer('wednesday')->default(0);
            $table->string('wed_from')->nullable();
            $table->string('wed_to')->nullable();


            $table->integer('thursday')->default(0);
            $table->string('thurs_from')->nullable();
            $table->string('thurs_to')->nullable();



            $table->integer('friday')->default(0);
            $table->string('fri_from')->nullable();
            $table->string('fri_to')->nullable();

            
            $table->integer('saturday')->default(0);
            $table->string('sat_from')->nullable();
            $table->string('sat_to')->nullable();

            $table->integer("vendor")->default(0);
            $table->string("active")->default(1);
            $table->integer('deleted')->default(0);
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
        Schema::dropIfExists('groomers');
    }
}
