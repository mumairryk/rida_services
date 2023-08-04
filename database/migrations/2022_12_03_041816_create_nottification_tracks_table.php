<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNottificationTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nottification_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('from_user_id')->default(0);
            $table->integer('to_user_id')->default(0);
            $table->string('firebase_node_id')->nullable();
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
        Schema::dropIfExists('nottification_tracks');
    }
}
