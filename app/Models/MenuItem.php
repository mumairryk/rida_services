<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = "menu_items";
    protected $guarded = [];

    function menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    function itemType(){
        return $this->belongsTo(MenuItemType::class, 'menu_item_type_id', 'id');
    }
}
