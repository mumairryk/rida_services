<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HidePost extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function post() {
        return $this->belongsTo('App\Models\Post', 'post_id', 'id');
    }
}
