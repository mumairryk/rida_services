<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            $table->string('bank_name',600)->nullable()->change();
            $table->string('company_account',600)->nullable()->change();
            $table->string('account_no',600)->nullable()->change();
            $table->string('branch_code',300)->nullable()->change();
            $table->string('branch_name',300)->nullable()->change();
            $table->string('bank_statement_doc',600)->nullable()->change();
            $table->string('credit_card_sta_doc',600)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_details', function (Blueprint $table) {
            //
        });
    }
}
