<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnPolicyFieldsToStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('ret_policy_title', 200)->nullable();
            $table->text('ret_policy_description')->nullable();
            $table->string('ret_policy_doc', 900)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('ret_policy_title');
            $table->dropColumn('ret_policy_description');
            $table->dropColumn('ret_policy_doc');
        });
    }
}
