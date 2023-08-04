<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->foreign('product_id')
            ->references('id')
            ->on('product')
            ->onDelete('cascade');
            $table->integer('product_attribute_id')->default(0);

            $table->integer('product_type')->default(0);
            $table->integer('quantity')->default(0);
            $table->double('price', 15, 2)->nullable();
            $table->double('discount', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->integer('vendor_id')->default(0);
            $table->double('admin_commission', 15, 2)->nullable();
            $table->double('vendor_commission', 15, 2)->nullable();
            $table->double('shipping_charge', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_order_products');
    }
}
