<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    use HasFactory;
    protected $table = "activity_type";
    protected $primaryKey = "id";

    protected $guarded = [];

    public function account(){
        return $this->belongsTo(AccountType::class, 'account_id')->withDefault([
            'name' => 'No Account',
        ]);
    }

}
