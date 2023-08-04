<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute', function (Blueprint $table) {
            $table->bigIncrements('attribute_id');
            $table->string('attribute_name',400)->nullable();
            $table->integer('attribute_status')->default(0);
            $table->dateTime('attribute_created_date')->nullable();
            $table->string('attribute_name_arabic',600);
            $table->string('attribute_type',100);
            $table->integer('is_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute');
    }
}
