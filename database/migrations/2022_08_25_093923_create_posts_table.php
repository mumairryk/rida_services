<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('caption',2500)->nullable();
            $table->integer('file_type')->default(1);//default image
            $table->text("file")->nullable();
            $table->text("location_name")->nullable();
            $table->string('lattitude',250)->nullable();
            $table->string('longitude',250)->nullable();
            $table->string('post_firebase_node_id',250)->nullable();
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
        Schema::dropIfExists('posts');
    }
}
