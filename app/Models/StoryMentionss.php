<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryMentionss extends Model
{
    use HasFactory;
    protected $table='story_mentions';
    public function story() {
        return $this->belongsTo('Stories', 'story_id', 'id');
    }
    public function user(){
      return $this->hasOne('App\Models\User','id','user_id');
    }
}
