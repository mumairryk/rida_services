<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVendorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_details', function (Blueprint $table) {
        $table->integer('user_id')->default(0);
        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        $table->string('logo',600)->nullable()->change();
        $table->string('company_name',600)->nullable()->change();
        $table->string('company_brand',600)->nullable()->change();
        $table->string('legal_staus',600)->nullable()->change();
        $table->string('trade_license',600)->nullable()->change();
        $table->string('vat_reg_number',600)->nullable()->change();
        $table->string('address1',600)->nullable()->change();
        $table->string('address2',600)->nullable()->change();
        $table->string('street',600)->nullable()->change();
        $table->string('zip',300)->nullable()->change();
        $table->string('trade_license_doc',600)->nullable()->change();
        $table->string('share_certificate_doc',600)->nullable()->change();
        $table->string('power_attorny_doc',600)->nullable()->change();
        $table->string('vat_reg_doc',600)->nullable()->change();
        $table->string('signed_agreement_doc',600)->nullable()->change();
        $table->string('identy1_doc',600)->nullable()->change();
        $table->string('identy2_doc',600)->nullable()->change();
        $table->string('company_identy1_doc',600)->nullable()->change();
        $table->string('company_identy2_doc',600)->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_details', function (Blueprint $table) {
            //
        });
    }
}
