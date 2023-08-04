<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->bigIncrements('coupon_id');
            $table->string('coupon_title',600)->nullable();
            $table->longText('coupon_description')->nullable();
            $table->dateTime('coupon_end_date')->nullable();
            $table->double('coupon_amount', 15, 2)->nullable();
            $table->double('coupon_minimum_spend', 15, 2)->nullable();
            $table->double('coupon_maximum_spend', 15, 2)->nullable();
            $table->double('coupon_usage_percoupon', 15, 2)->nullable();
            $table->double('coupon_usage_perx', 15, 2)->nullable();
            $table->double('coupon_usage_peruser', 15, 2)->nullable();
            $table->dateTime('coupon_created_date')->nullable();
            $table->integer('coupon_vender_id')->default(0);
            $table->string('coupon_code',100)->nullable();
            $table->integer('coupon_status')->default(0);
            $table->integer('individual_use')->default(0);
            $table->integer('amount_type')->default(0);
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
        Schema::dropIfExists('coupon');
    }
}
