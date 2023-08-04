<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageAttribute($value)
    {
        if ($value) {
            return get_uploaded_image_url($value, 'food_image_upload_dir');
            return asset($value);
        } else {
            return '';
        }
    }
}
