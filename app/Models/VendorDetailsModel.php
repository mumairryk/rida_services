<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDetailsModel extends Model
{
    use HasFactory;
    protected $table = "vendor_details";
    public $timestamps = true;


    public function getLogoAttribute($value)
    {
        if($value)
        {
            return asset($value);
        }
        else
        {
            return asset('uploads/company/17395e3aa87745c8b488cf2d722d824c.jpg');
        }
    }
    public function industry_type(){
        return $this->hasOne('App\Models\IndustryTypes', 'id', 'industry_type');
    }
    public function country() {
       return $this->belongsTo(CountryModel::class,'country_id');
    }
    public function city() {
       return $this->belongsTo(Cities::class,'city_id');
    }
    public function state() {
       return $this->belongsTo(States::class,'state_id');
    }
    public function holiday_dates()
    {
        return $this->hasMany(VendorHolidayDates::class, 'vendor_id', 'id');
    }
}
