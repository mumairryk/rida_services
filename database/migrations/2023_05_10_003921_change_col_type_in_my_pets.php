<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColTypeInMyPets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_pets', function (Blueprint $table) {
            $table->dropColumn('species');
        });

        Schema::table('my_pets', function (Blueprint $table) {
            $table->integer('species');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_pets', function (Blueprint $table) {
            //
        });
    }
}
