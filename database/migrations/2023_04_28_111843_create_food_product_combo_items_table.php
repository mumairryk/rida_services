<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodProductComboItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_product_combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_product_combo_id')->constrained('food_product_combos')->onDelete('cascade');
            $table->foreignId('food_item_id')->constrained('food_products');
            $table->boolean('is_default')->default(false);
            $table->float('extra_price')->default(0);
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
        Schema::dropIfExists('food_product_combo_items');
    }
}
