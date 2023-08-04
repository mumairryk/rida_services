<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function serviceRequestImages(){
        return $this->hasMany('App\Models\ServiceRequestImage', 'service_request_id');
    }

    function store(){
        return $this->belongsTo('App\Models\User', 'store_id');
    }
}
