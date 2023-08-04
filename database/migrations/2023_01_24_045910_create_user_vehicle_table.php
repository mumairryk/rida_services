<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vehicle', function (Blueprint $table) {
            $table->id();
            $table->integer('vehicle_type')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('vehicle_front', 600)->nullable();
            $table->string('vehicle_back', 600)->nullable();
            $table->string('vehicle_registration', 600)->nullable();
            $table->string('driving_license', 600)->nullable();
            $table->integer('deleted')->nullable()->default(0);
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
        Schema::dropIfExists('user_vehicle');
    }
}