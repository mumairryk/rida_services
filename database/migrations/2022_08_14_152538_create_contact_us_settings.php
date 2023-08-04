<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title_en',300)->nullable();
            $table->string('title_ar',300)->nullable();
            $table->string('email',300)->nullable();
            $table->string('mobile',50)->nullable();
            $table->longText('desc_en')->nullable();
            $table->longText('desc_ar')->nullable();
            $table->longText('location')->nullable();
            $table->longText('latitude')->nullable();
            $table->longText('longitude')->nullable();
            $table->string('twitter',600)->nullable();
            $table->string('instagram',600)->nullable();
            $table->string('facebook',600)->nullable();
            $table->string('youtube',600)->nullable();
            $table->string('linkedin',600)->nullable();
            $table->timestamps();
        });
        DB::table('contact_us_settings')->insert(
          [
            'title_en' => 'MODA'
          ]
       );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_us_settings');
    }
}
