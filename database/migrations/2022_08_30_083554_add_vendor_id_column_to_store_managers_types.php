<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorIdColumnToStoreManagersTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_managers_types', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->index('man_vendor_id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_managers_types', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
        });
    }
}
