<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletPaymentReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_payment_report', function (Blueprint $table) {
            $table->id();            
            $table->string('transaction_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->integer('user_id')->default(0);  
            $table->string('ref_id')->nullable();   
            $table->decimal('amount');
            $table->integer('method_type')->default(1);  
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
        Schema::dropIfExists('wallet_payment_report');
    }
}
