<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorServiceTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_service_timings', function (Blueprint $table) {
            $table->id();
            $table->integer("service_id")->default(0);
            $table->integer("vendor")->default(0);
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
        Schema::dropIfExists('vendor_service_timings');
    }
}
