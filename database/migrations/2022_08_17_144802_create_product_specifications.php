<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSpecifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->bigIncrements('prod_spec_id');
            $table->integer('product_id')->default(0);
            $table->foreign('product_id')
            ->references('id')
            ->on('product')
            ->onDelete('cascade');
            $table->longText('spec_title')->nullable();
            $table->longText('spec_descp')->nullable();
            $table->integer('lang')->default(1)->nullable();
            $table->longText('spec_title_arabic')->nullable();
            $table->longText('spec_descp_arabic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_specifications');
    }
}
