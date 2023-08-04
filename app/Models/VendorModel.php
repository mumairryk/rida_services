<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class VendorModel extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "users";
    public $timestamps = false;

    // protected $fillable = ['name', 'email', 'dial_code','phone','role','first_name','user_name','last_name','user_image',
    // 'password','country_id','state_id','city_id','area','vendor','store','previllege','created_at','updated_at','designation_id','active','dob'];

    protected $guarded = [];
    

    public function getUserImageAttribute($value)
    {
        if($value)
        {
            return get_uploaded_image_url($value,'user_image_upload_dir');
            return asset($value);
        }
        else
        {
            return get_uploaded_image_url($value,'user_image_upload_dir');
        }
    }
    public function vendordata() {
       return $this->hasMany(VendorDetailsModel::class,'user_id');
    }
    public function doggyplay_time_dates()
    {
        return $this->hasMany(DoggyPlayTimeDates::class, 'vendor_id','id');
    }
    public function stores() {
        return $this->hasMany('App\Models\Stores', 'vendor_id', 'id'); 
    }
    public function holiday_dates()
    {
        return $this->hasMany(VendorHolidayDates::class, 'vendor_id', 'id');
    }

    function menu(){
        $this->hasOne(Menu::class, 'vendor_id', 'id');
    }
    public function user_location(){
        return $this->hasOne('App\Models\UserLocations', 'user_id', 'id');
    }
    public function getBannerImageAttribute($value){
        return get_uploaded_image_url($value,'user_image_upload_dir');
      }

}
