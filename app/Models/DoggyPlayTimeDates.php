<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoggyPlayTimeDates extends Model
{
    protected $guarded = [];
    public function vendor()
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id');
    }
    public function service()
    {
        return $this->belongsTo(VendorModel::class, 'service_id');
    }
}
