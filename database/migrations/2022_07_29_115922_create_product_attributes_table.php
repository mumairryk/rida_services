<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer("product_id");
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->double("regular_price")->default(0);
            $table->double("sale_price")->default(0);
            $table->integer("rating")->default(0);
            $table->integer("rated_users")->default(0);
            $table->text("image_temp");
            $table->string("image_action",20);
            $table->timestamps();
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
