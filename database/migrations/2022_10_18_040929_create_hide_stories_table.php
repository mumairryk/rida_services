<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHideStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hide_stories', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("Cascade");
            $table->integer("hidden_user_id");
            $table->foreign("hidden_user_id")->references("id")->on("users")->onDelete("Cascade");
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
        Schema::dropIfExists('hide_stories');
    }
}
