<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_type', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_type_name');
            $table->string('attribute_type_uid', 50);
            $table->integer('attribute_type_status')->default(1);
        });
        \DB::table('attribute_type')->insert(
            [

                [
                    'attribute_type_name' => 'Dropdown',
                    'attribute_type_uid' => 'dropdown',
                    'attribute_type_status' => 1,
                ],
                [
                    'attribute_type_name' => 'Button',
                    'attribute_type_uid' => 'radio',
                    'attribute_type_status' => 1,
                ],
                [
                    'attribute_type_name' => 'Image Selection',
                    'attribute_type_uid' => 'radio_image',
                    'attribute_type_status' => 1,
                ],
                [
                    'attribute_type_name' => 'Color Box',
                    'attribute_type_uid' => 'radio_button_group',
                    'attribute_type_status' => 1,
                ],
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
        Schema::dropIfExists('attribute_type');
    }
}
