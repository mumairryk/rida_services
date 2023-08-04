<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AmountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_type', function (Blueprint $table) {
            $table->id();
            $table->string('name',600);
        });
        DB::table('amount_type')->insert(
        [
          [
            'name' => '%'
          ],
          [
            'name' => 'Amount'
          ]
       ]
       );
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
