<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModaSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moda_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('main_category');
            $table->string('name')->nullable();
            $table->integer('gender')->comment('1.Male,2.female');
            $table->string('image', 900)->nullable();
            $table->smallInteger('active')->default(1);
            $table->integer('sort_order')->default(0);
            $table->integer('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moda_sub_categories');
    }
}
