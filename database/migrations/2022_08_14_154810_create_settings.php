<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->double('admin_commission', 15, 2);
            $table->double('shipping_charge', 15, 2);
            $table->timestamps();
        });

        DB::table('settings')->insert(
          [
            'admin_commission' => '0.00',
            'shipping_charge' => '0.00'
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
        Schema::dropIfExists('settings');
    }
}
