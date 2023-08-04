<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoriesAddLiveDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('stories', function (Blueprint $table) {
            $table->integer('is_live')->default(0);
            $table->text('live_url')->nullable();
            $table->string('channel_id')->nullable();
            $table->string('zoom')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('channel_key')->nullable();
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
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn('is_live');
            $table->dropColumn('live_url');
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('channel_id');
            $table->dropColumn('channel_key');
        });
    }
}
