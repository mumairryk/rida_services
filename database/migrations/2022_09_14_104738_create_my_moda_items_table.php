<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyModaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_moda_items', function (Blueprint $table) {
            $table->id();
            $table->integer('my_moda_id');
            $table->integer('product_id');
            $table->integer('product_attribute_id');
            $table->integer('moda_sub_category');
            $table->integer('user_id');
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
        Schema::dropIfExists('my_moda_items');
    }
}
