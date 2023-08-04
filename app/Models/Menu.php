<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = "menus";
    protected $guarded = [];

    function items(){
        return $this->hasMany(MenuItem::class, 'menu_id', 'id');
    }

    function vendor(){
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'id');
    }

}
