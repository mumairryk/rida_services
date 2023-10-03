<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Materials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_selected_attribute_list', function (Blueprint $table) {
            $table->longText('material')->nullable();
            $table->longText('product_details')->nullable();
            $table->longText('needtoknow')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_selected_attribute_list', function (Blueprint $table) {
            //
        });
    }
}
