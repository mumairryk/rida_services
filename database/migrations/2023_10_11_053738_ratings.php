<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ratings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('product_varient_id')->default(0);
            $table->integer('service_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->double('rating', 15, 2)->default(0);
            $table->longText('title')->nullable();
            $table->longText('comment')->nullable();
            $table->integer('order_id')->default(0);
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
        Schema::dropIfExists('ratings');
    }
}
