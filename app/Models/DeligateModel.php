<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeligateModel extends Model
{
    use HasFactory;
    protected $table = "deligates";
    protected $primaryKey = "id";

    protected $fillable = ['deligate_name','deligate_icon','deligate_status','shipping_charge'];

    public function getDeligateIconAttribute($deligate_icon){

        return get_uploaded_image_url( $deligate_icon, 'deligates_upload_dir', 'placeholder.png' );

        
    }
}
