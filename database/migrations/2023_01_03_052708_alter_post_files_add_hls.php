<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostFilesAddHls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('post_files', function (Blueprint $table) {
            $table->integer('have_hls_url')->default(0);
            $table->text('hls_url')->nullable();
            $table->text('hls_cdn_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_files', function (Blueprint $table) {
            $table->dropColumn('have_hls_url');
            $table->dropColumn('hls_url');
            $table->dropColumn('hls_cdn_url');
        });
    }
}
