<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        if($value)
        {
            return get_uploaded_image_url($value,'user_image_upload_dir');
            return asset($value);
        }
        else
        {
            return '';
        }
    }
    public function getBackgroundImageAttribute($value)
    {
        if($value)
        {
            return get_uploaded_image_url($value,'user_image_upload_dir');
            return asset($value);
        }
        else
        {
            return '';
        }
    }
    public function doggyplay_time_dates()
    {
        return $this->hasMany(DoggyPlayTimeDates::class, 'service_id','id');
    }
}
