<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->bigIncrements('attribute_values_id');
            $table->integer('attribute_id')->default(0);
            $table->string('attribute_values',600);
            $table->string('attribute_values_arabic',600);
            $table->integer('attribute_value_in')->default(1);
            $table->string('attribute_value_color',600);
            $table->string('attribute_value_label',600);
            $table->string('attribute_value_label_arabic',600);
            $table->string('attribute_value_image',600);
            $table->integer('is_deleted')->default(0);
            $table->integer('attribute_value_sort_order')->default(0);
            $table->string('attribute_color',600);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
}
