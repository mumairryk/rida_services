<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodProductComboItems extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function foodItem()
    {
        return $this->belongsTo(FoodItems::class, 'food_item_id');
    }

    public function foodCombo()
    {
        return $this->belongsTo(FoodProductCombo::class, 'food_product_combo_id');
    }
}
