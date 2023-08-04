<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductAttributeValuesChangeAttributeValueLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->string('attribute_value_label',600)->nullable()->change();
            $table->string('attribute_value_label_arabic',600)->nullable()->change();
            $table->string('attribute_value_image',600)->nullable()->change();
            $table->string('attribute_color',600)->nullable()->change();
            $table->string('attribute_values',600)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            //
        });
    }
}
