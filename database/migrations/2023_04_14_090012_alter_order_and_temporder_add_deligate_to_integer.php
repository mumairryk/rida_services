<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderAndTemporderAddDeligateToInteger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_orders', function (Blueprint $table) {
            $table->dropColumn('request_deligate');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('request_deligate');
        });

        Schema::table('temp_orders', function (Blueprint $table) {
            $table->integer('request_deligate')->default(0);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('request_deligate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
