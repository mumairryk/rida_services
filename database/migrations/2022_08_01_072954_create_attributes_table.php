<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name');
            $table->integer('active')->default(1);
            $table->integer('attribute_type');
            $table->integer('industry_type');
            $table->integer('company_id')->default(0);
            $table->integer('store_id')->default(0);
            $table->integer('is_common')->default(1);
            $table->integer('deleted')->default(0);
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
        Schema::dropIfExists('attributes');
    }
}
