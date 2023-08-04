<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersAddExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->integer("is_private_profile")->default(0);
            $table->string("user_name",150)->nullable();
            $table->integer("gender")->default(1);
            $table->string("website",2500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColoumn("is_private_profile");
            $table->dropColoumn("user_name",150);
            $table->dropColoumn("gender");
            $table->dropColoumn("website",2500);
        });
    }
}
