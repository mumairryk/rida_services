<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModaMainCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moda_main_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        \DB::table('moda_main_categories')->insert([
            [
                'name' => 'Head',
                'created_at' => gmdate('Y-m-d H:i:s')
            ],
            [
                'name' => 'Upper Body',
                'created_at' => gmdate('Y-m-d H:i:s')
            ],
            [
                'name' => 'Lower Body',
                'created_at' => gmdate('Y-m-d H:i:s')
            ],
            [
                'name' => 'Footwear',
                'created_at' => gmdate('Y-m-d H:i:s')
            ],
            [
                'name' => 'Makeup',
                'created_at' => gmdate('Y-m-d H:i:s')
            ],
            [
                'name' => 'Accessories',
                'created_at' => gmdate('Y-m-d H:i:s')
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
        Schema::dropIfExists('moda_main_categories');
    }
}
