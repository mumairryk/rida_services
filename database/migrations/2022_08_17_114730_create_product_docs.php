<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->default(0);
            $table->foreign('product_id')
            ->references('id')
            ->on('product')
            ->onDelete('cascade');
            $table->string('title',600)->nullable();
            $table->string('doc_path',600)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_docs');
    }
}
