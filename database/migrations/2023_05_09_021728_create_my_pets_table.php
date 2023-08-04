<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyPetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_pets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name',600);
            $table->string('species',600);
            $table->integer('breed_id');
            $table->integer('sex');
            $table->date('dob');
            $table->double('weight', 15, 2);
            $table->string('food',1200);
            $table->text('additional_notes');
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
        Schema::dropIfExists('my_pets');
    }
}
