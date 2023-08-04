<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function report_user() {
        return $this->belongsTo('App\Models\User', 'reported_user_id', 'id');
    }
}
