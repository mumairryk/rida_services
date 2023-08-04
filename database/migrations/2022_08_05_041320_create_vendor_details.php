<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_details', function (Blueprint $table) {
            $table->id();
            $table->integer('homedelivery')->default(0);
            $table->string('logo',600);
            $table->integer('key_control')->default(0);
            $table->integer('branches')->default(0);
            $table->string('company_name',600);
            $table->string('company_brand',600);
            $table->string('legal_staus',600);
            $table->dateTime('reg_date')->nullable();
            $table->string('trade_license',600);
            $table->dateTime('trade_license_expiry')->nullable();
            $table->string('vat_reg_number',600);
            $table->dateTime('vat_reg_expiry')->nullable();
            $table->string('address1',600);
            $table->string('address2',600);
            $table->string('street',600);
            $table->integer('state')->default(0);
            $table->integer('city')->default(0);
            $table->integer('area')->default(0);
            $table->string('zip',600);
            $table->string('trade_license_doc',600);
            $table->string('chamber_of_commerce_doc',600);
            $table->string('share_certificate_doc',600);
            $table->string('power_attorny_doc',600);
            $table->string('vat_reg_doc',600);
            $table->string('signed_agreement_doc',600);
            $table->integer('identy1_type')->default(0);
            $table->string('identy1_doc',600);
            $table->integer('identy2_type')->default(0);
            $table->string('identy2_doc',600);
            $table->integer('company_identy1_type')->default(0);
            $table->string('company_identy1_doc',600);
            $table->integer('company_identy2_type')->default(0);
            $table->string('company_identy2_doc',600);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_details');
    }
}
