<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterServiceRequestAddExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('service_requests', function (Blueprint $table) {
            $table->date('service_date')->nullable();
            $table->time('service_time')->nullable();
            $table->text('location_name')->nullable();
            $table->text('service_invoice_id')->nullable();
            $table->renameColumn('vendor_id','store_id');
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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn('service_date');
            $table->dropColumn('service_time');
            $table->dropColumn('location_name');
            $table->renameColumn('store_id','vendor_id');
        });
    }
}
