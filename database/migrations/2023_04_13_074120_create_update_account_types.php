<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AccountType;

class CreateUpdateAccountTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_type', function (Blueprint $table) {
            AccountType::where('id',1)->update(['name' => 'Commercial Centers(SHOPS)']);
            AccountType::where('id',2)->update(['name' => 'Reservations']);
            AccountType::where('id',3)->update(['name' => 'Individuals']);
            AccountType::where('id',4)->update(['name' => 'Services Providers']);
            AccountType::where('id',5)->update(['name' => 'WholeSellers']);
            AccountType::where('id',6)->update(['name' => 'Delivery Representative']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_type', function (Blueprint $table) {
            //
        });
    }
}
