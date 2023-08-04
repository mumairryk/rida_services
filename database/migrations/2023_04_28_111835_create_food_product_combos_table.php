<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodProductCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_product_combos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_product_id')->constrained('food_products')->onDelete('cascade');
            $table->foreignId('food_heading_id')->constrained('food_headings');
            $table->boolean('is_required')->default(false);
            $table->integer('min_select')->nullable();
            $table->integer('max_select')->nullable();
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
        Schema::dropIfExists('food_product_combos');
    }
}
