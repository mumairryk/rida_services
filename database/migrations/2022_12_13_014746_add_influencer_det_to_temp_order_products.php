<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfluencerDetToTempOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('temp_order_products', function (Blueprint $table) {
            $table->integer('influencer_id')->default(0);
            $table->integer('influencer_qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_order_products', function (Blueprint $table) {
            $table->dropColumn('influencer_id');
            $table->dropColumn('influencer_qty');
        });
    }
}
