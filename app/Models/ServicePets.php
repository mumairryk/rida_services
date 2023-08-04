<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePets extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public function pets(){
        return $this->belongsTo('App\Models\MyPets', 'pet_id', 'id');
    }
}
