<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFoodProductComboItemsConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_product_combo_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
            $table->foreign('food_item_id')->references('id')->on('food_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_product_combo_items', function (Blueprint $table) {
            $table->dropForeign(['food_item_id']);
            $table->foreign('food_item_id')->references('id')->on('food_products')->onDelete('cascade');
        });
    }
}
