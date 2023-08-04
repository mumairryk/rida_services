<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCodeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_code_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',500)->nullable();
            $table->integer('country_id')->default(0);
            $table->integer('active')->default(0);
            $table->integer('deleted')->default(0);
            $table->timestamps();
        });

        DB::table('bank_code_types')->insert(
        [
          [
            'name' => 'IFSC (India)'
          ],
          [
            'name' => 'SORT CODE (UK)'
          ],
          [
            'name' => 'SWIFT CODE',
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
        Schema::dropIfExists('bank_code_types');
    }
}
