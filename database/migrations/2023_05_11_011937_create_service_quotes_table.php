<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_quotes', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->integer('user_id');
            $table->integer('pet_id');

            $table->integer('doctor_id')->default(0)->nullable();
            $table->integer('groomer_id')->default(0)->nullable();
            $table->integer('appointment_type')->default(0)->nullable();

            $table->string('time',100)->nullable();
            $table->string('date',100)->nullable();

            $table->string('drop_off_time',100)->nullable();
            $table->string('drop_off_date',100)->nullable();

            $table->string('pick_up_time',100)->nullable();
            $table->string('pick_up_date',100)->nullable();

            $table->integer('feeding_schedule')->default(0)->nullable();

            $table->text('food')->nullable();
            $table->integer('specific_medication')->default(0)->nullable();
            $table->text('notes')->nullable();

            $table->string("status")->default(1);
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
        Schema::dropIfExists('service_quotes');
    }
}
