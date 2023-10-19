<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\VendorModel;
use App\Models\EnquiryDetails;

class Enquiry extends Model
{

    protected $table = 'enquiry';

    function customer(){
        return $this->hasOne(VendorModel::class, 'id', 'user_id');
    }

    function enquiery_details(){
        return $this->hasMany(EnquiryDetails::class, 'enquiry_id', 'id');
    }
}
