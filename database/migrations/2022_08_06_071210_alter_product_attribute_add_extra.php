<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductAttributeAddExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->integer('stock')->default(0);
            $table->string('sku',500);
            $table->string('barcode',500);
            $table->string('weight',500);
            $table->string('height',500);
            $table->string('length',500);
            $table->string('width',500);
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
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->dropColumn('sku');
            $table->dropColumn('barcode');
            $table->dropColumn('weight');
            $table->dropColumn('height');
            $table->dropColumn('length');
            $table->dropColumn('width');
        });
    }
}
