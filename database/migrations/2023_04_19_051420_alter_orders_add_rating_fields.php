<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersAddRatingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('driver_id')->default(0);
            $table->double('driver_rating')->default(0);
            $table->text('driver_review')->nullable();
            $table->double('store_rating')->default(0);
            $table->text('store_review')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('driver_id');
            $table->dropColumn('driver_rating');
            $table->dropColumn('driver_review');
            $table->dropColumn('store_rating');
            $table->dropColumn('store_review');
        });
    }
}
