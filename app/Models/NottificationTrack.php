<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NottificationTrack extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo('App\Models\User', 'from_user_id', 'id');
    }
    public function followed_user() {
        return $this->belongsTo('App\Models\User', 'to_user_id', 'id');
    }
}
