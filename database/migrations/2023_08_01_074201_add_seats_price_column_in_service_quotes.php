<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatsPriceColumnInServiceQuotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_quotes', function (Blueprint $table) {
            $table->integer('seats')->nullable()->default(0);
            $table->string('paid_price')->nullable()->default('0');

            $table->double('total', 15, 2)->default(0)->nullable();
            $table->double('vat', 15, 2)->default(0)->nullable();
            $table->double('discount', 15, 2)->default(0)->nullable();
            $table->double('grand_total', 15, 2)->default(0)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_quotes', function (Blueprint $table) {
            $table->dropColumn('seats');
            $table->dropColumn('paid_price');
            $table->dropColumn('total');
            $table->dropColumn('vat');
            $table->dropColumn('discount');
            $table->dropColumn('grand_total');
            
        });
    }
}
