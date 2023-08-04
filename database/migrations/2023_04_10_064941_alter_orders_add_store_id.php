<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersAddStoreId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->integer('store_id')->default(0);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('store_id')->default(0);
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
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }
}
