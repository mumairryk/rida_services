<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostCommentsAddBadWordsCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('post_comments', function (Blueprint $table) {
            $table->text('bad_words')->nullable();
            $table->integer('is_bad_word_exist')->default(0);
            $table->integer('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropColumn('bad_words');
            $table->dropColumn('is_bad_word_exist');
        });
    }
}
