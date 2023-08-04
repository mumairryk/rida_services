<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyPets extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        if ($value) {
            return get_uploaded_image_url($value, 'pet_image_upload_dir');
            return asset($value);
        } else {
            return '';
        }
    }
    public function breed(){
        return $this->belongsTo('App\Models\Breeds', 'breed_id', 'id');
    }

    public function sps(){
        return $this->belongsTo('App\Models\Species', 'species', 'id');
    }
}
