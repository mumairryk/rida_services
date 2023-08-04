<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->boolean('shared_product')->default(0);
            $table->unsignedBigInteger('store_id')->nullable();
            $table->boolean('is_editable_by_outlets')->default(0);
            $table->string('product_name')->nullable();
            $table->float('regular_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->smallInteger('is_veg')->default(0)->comment('0: Non-Veg, 1: Veg, 2: Egg');
            $table->smallInteger('promotion')->default(0)->comment('0: No, 1: Buy 1 Get 1, 2: Buy 2 Get 1');
            $table->longText('product_images')->nullable();
            $table->text('description')->nullable();
            $table->integer('deleted')->default(0);
            $table->integer('product_status')->default(0);
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
        Schema::dropIfExists('food_products');
    }
}
