<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image',1500)->nullable();;
            $table->string('banner_image',1500)->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('active')->default(1);
            $table->integer('deleted')->default(0);
            $table->integer('sort_order')->default(0);
            $table->integer('created_uid');
            $table->integer('updated_uid')->nullable();
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
        Schema::dropIfExists('division');
    }
}
