<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_address', function (Blueprint $table) {
            
            $table->dropColumn('dial_code');
            $table->dropColumn('phone');

            $table->renameColumn('full_name','street');
            $table->renameColumn('building_name', 'building');
            $table->renameColumn('flat_no', 'apartment');
            $table->renameColumn('address', 'location');
            $table->dropColumn('postcode');
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
    }
}
