<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;

    protected $table = "coupon";

    public $timestamps = false;

    public function getAppliedToText()
    {
        if ($this->applied_to == '3') {
            $text = 'Food Product';
        } elseif ($this->applied_to == '2') {
            $text = 'Product';
        } elseif ($this->applied_to == '1') {
            $text = 'Category';
        }

        return $text;
    }
}
