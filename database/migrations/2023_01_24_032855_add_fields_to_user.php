<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('commercial_license', 600)->nullable();
            $table->string('commercial_reg_no', 600)->nullable();
            $table->text('associated_license', 600)->nullable();
            $table->integer('res_dial_code')->nullable();
            $table->integer('res_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('commercial_license');
            $table->dropColumn('commercial_reg_no');
            $table->dropColumn('associated_license');
            $table->dropColumn('res_dial_code');
            $table->dropColumn('res_phone');
        });
    }
}