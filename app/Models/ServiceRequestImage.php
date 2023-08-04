<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestImage extends Model
{
    use HasFactory;
    public function getImageAttribute($value){
        return get_uploaded_image_url( $value, 'service_image_upload_dir', 'placeholder.png' );
    }
}
