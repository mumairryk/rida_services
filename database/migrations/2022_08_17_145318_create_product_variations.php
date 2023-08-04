<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->bigIncrements('product_variations_id');
            $table->integer('attribute_id')->default(0);
            $table->integer('attribute_values_id')->default(0);
            $table->integer('product_attribute_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->foreign('product_id')
            ->references('id')
            ->on('product')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
