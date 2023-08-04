<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
     protected $hidden = [
         'password',
         'remember_token',
         'email_verified_at',
         'previllege',
         'password_reset_otp',
         'password_reset_time',
         'password_reset_code'
     ];
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];
    protected $appends = ['profile_url'];
    public function getProfileUrlAttribute(){
     $url = url('profile/'. $this->id);
     return $url;
    }


    public static function update_password($id,$password){
        return DB::table("users")->where("id",'=',$id)->update(['password' =>bcrypt($password)]);
    }
    public function getUserImageAttribute($value){
        // if($this->role == 3 || $this->role == 4){
        //     return public_url().$value;
        // }
      return get_uploaded_image_url($value,'user_image_upload_dir');
    }
    public function getBannerImageAttribute($value){
      return get_uploaded_image_url($value,'user_image_upload_dir');
    }
    public function posts() {
        return $this->hasMany('App\Models\Post', 'user_id', 'id');
    }
    public function followed(){
      return $this->hasMany('App\Models\UserFollow','user_id', 'id');
    }
    public function follower(){
      return $this->hasMany('App\Models\UserFollow','follow_user_id', 'id');
    }
    public function vendordata() {
       return $this->hasOne(VendorDetailsModel::class,'user_id');
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
    public function bank_details() {
       return $this->hasOne(BankdataModel::class,'user_id');
    }
    public function cart(){
      return $this->hasMany('App\Models\Cart','user_id', 'id');
    }
    public function stories(){
      return $this->hasMany('App\Models\Stories','user_id', 'id');
    }
    public function location_data() {
      return $this->hasOne(UserLocations::class,'user_id');
   }
}
