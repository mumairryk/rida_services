<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type',600)->nullable();
            $table->string('notifiable_type',600)->nullable();
            $table->integer('notifiable_id')->default(0);
            $table->string('related_to',600)->nullable();
            $table->longText('data')->nullable();
            $table->dateTime('read_at')->nullable();
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
        Schema::dropIfExists('db_notifications');
    }
}
