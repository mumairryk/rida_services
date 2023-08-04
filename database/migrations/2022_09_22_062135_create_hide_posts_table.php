<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHidePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hide_posts', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer("post_id");
            $table->foreign("post_id")->references('id')->on('posts')->onDelete('cascade');
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
        Schema::dropIfExists('hide_posts');
    }
}
