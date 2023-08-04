<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $appends = ['story_url'];
    public function getStoryUrlAttribute(){
     $url = url('story/'. $this->id);
     return $url;
    }
    public function post_users() {
        return $this->hasMany('App\Models\StoryMentionss', 'story_id', 'id');
    }
    public function getPathAttribute($value){
      return get_uploaded_image_url($value,'post_image_upload_dir');
    }
    public function getLiveUrlAttribute($value){
        if($value != ''){
            return get_uploaded_image_url($value,'post_image_upload_dir');
        }else{
            return '';
        }
    }
    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
