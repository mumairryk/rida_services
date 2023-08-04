<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id',100)->nullable();
            $table->integer('user_id')->default(0);
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->integer('address_id')->default(0);
            $table->double('total', 15, 2)->default(0)->nullable();
            $table->double('vat', 15, 2)->default(0)->nullable();
            $table->double('discount', 15, 2)->default(0)->nullable();
            $table->double('grand_total', 15, 2)->default(0)->nullable();
            $table->integer('payment_mode')->default(0);
            $table->double('admin_commission', 15, 2)->default(0)->nullable();
            $table->double('vendor_commission', 15, 2)->default(0)->nullable();
            $table->double('shipping_charge', 15, 2)->default(0)->nullable();
            $table->string('temp_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_orders');
    }
}
