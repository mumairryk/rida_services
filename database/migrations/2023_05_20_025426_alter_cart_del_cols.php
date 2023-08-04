<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCartDelCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('influencer_id');
            $table->dropColumn('influencer_qty');
        });
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('influencer_id');
            $table->dropColumn('influencer_qty');
        });
        Schema::table('temp_order_products', function (Blueprint $table) {
            $table->dropColumn('influencer_id');
            $table->dropColumn('influencer_qty');
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
    }
}
