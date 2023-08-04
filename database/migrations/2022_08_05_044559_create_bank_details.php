<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name',600);
            $table->string('company_account',600);
            $table->integer('code_type')->default(0);
            $table->string('account_no',600);
            $table->string('branch_code',300);
            $table->string('branch_name',300);
            $table->string('bank_statement_doc',600);
            $table->string('credit_card_sta_doc',600);
            $table->integer('country')->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
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
        Schema::dropIfExists('bank_details');
    }
}
