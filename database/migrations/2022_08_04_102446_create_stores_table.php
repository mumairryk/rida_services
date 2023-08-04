<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id');
            $table->integer('industry_type')->nullable()->default(0);
            $table->string('store_name', 1000)->nullable();
            $table->string('business_email', 250)->nullable();
            $table->string('dial_code', 5)->nullable();
			$table->string('mobile', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('location', 1500);
            $table->string('latitude', 50);
			$table->string('longitude', 50);
            $table->string('address_line1',900)->nullable();
            $table->string('address_line2')->nullable();
            $table->bigInteger('country_id');
            $table->bigInteger('state_id');
            $table->bigInteger('city_id');
            $table->string('zip', 10)->nullable();
            $table->string('logo', 900)->nullable();
			$table->string('cover_image', 900)->nullable();
            $table->string('license_number', 100)->nullable();
			$table->string('license_doc', 900)->nullable();
			$table->string('vat_cert_number', 100)->nullable();
			$table->string('vat_cert_doc', 900)->nullable();
            $table->smallInteger('active')->default(1);
			$table->smallInteger('verified')->default(0);
            $table->integer('deleted')->default(0);
            $table->bigInteger('created_uid')->nullable();
            $table->bigInteger('updated_uid')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
