<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->integer('attribute_id');
            $table->string('attribute_values');
            $table->integer('attribute_value_in')->default(1);
            $table->string('attribute_value_color',30)->nullable();
            $table->integer('deleted')->default(0);
            $table->integer('created_uid');
            $table->integer('updated_uid')->nullable();
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
        Schema::dropIfExists('attribute_values');
    }
}
