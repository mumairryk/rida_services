<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodProductCombo extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo(FoodProduct::class, 'food_product_id');
    }

    public function foodHeading()
    {
        return $this->belongsTo(FoodHeading::class, 'food_heading_id');
    }

    public function combo_items()
    {
        return $this->hasMany(FoodProductComboItems::class, 'food_product_combo_id');
    }
}
