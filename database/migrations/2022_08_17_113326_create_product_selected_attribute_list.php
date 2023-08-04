<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSelectedAttributeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_selected_attribute_list', function (Blueprint $table) {
            $table->bigIncrements('product_attribute_id');
            $table->integer('product_id')->default(0);
            $table->integer('manage_stock')->default(0);
            $table->double('stock_quantity', 15, 2)->nullable();
            $table->integer('allow_back_order')->default(0);
            $table->integer('stock_status')->default(0);
            $table->integer('sold_individually')->default(0);
            $table->double('weight', 15, 2)->nullable();
            $table->double('length', 15, 2)->nullable();
            $table->double('height', 15, 2)->nullable();
            $table->double('width', 15, 2)->nullable();
            $table->integer('shipping_class')->default(0);
            $table->double('sale_price', 15, 2)->nullable();
            $table->double('regular_price', 15, 2)->nullable();
            $table->integer('taxable')->default(0);
            $table->string('image',600)->nullable();
            $table->string('shipping_note',600)->nullable();
            $table->string('title',600)->nullable();
            $table->double('rating', 15, 2)->nullable();
            $table->integer('rated_users')->default(0);
            $table->string('image_temp',600)->nullable();
            $table->string('barcode',600)->nullable();
            $table->string('image_action',600)->nullable();
            $table->string('pr_code',600)->nullable();
            $table->longText('product_desc')->nullable();
            $table->longText('product_full_descr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_selected_attribute_list');
    }
}
