<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTempOrdersAddDeligate extends Migration
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
            $table->string('request_deligate')->nullable();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('request_deligate')->nullable();
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
            $table->dropColumn('request_deligate');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('request_deligate');
        });
    }
}
