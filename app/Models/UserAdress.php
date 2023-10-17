<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAdress extends Model
{
    use HasFactory;
    protected $table = "user_address";
    public static function get_address_list($user_id)
    {
        $list = UserAdress::where(['status' => 1, 'user_id' => $user_id])->orderBy('id', 'DESC')
        ->leftjoin('country', 'country.id', 'user_address.country_id')
        ->leftjoin('states', 'states.id', 'user_address.state_id')
        ->leftjoin('cities', 'cities.id', 'user_address.city_id')
        ->get(['user_address.id','full_name','user_address.dial_code','phone', 'street', 'user_address.location', 'user_address.address_type', 'is_default', 'land_mark', 'building', 'latitude', 'longitude','apartment','user_address.state_id','user_address.city_id','states.name as state','cities.name as city']);
       
        return $list;
    }
    public static function get_address_details($address_id)
    {
        $adress = UserAdress::where(['user_address.id' => $address_id])->select('user_address.id', 'full_name','user_address.dial_code','phone', 'street', 'user_address.location', 'user_address.address_type', 'is_default', 'land_mark', 'building', 'latitude', 'longitude','apartment','user_address.state_id','user_address.city_id','states.name as state','cities.name as city')
            ->leftjoin('country', 'country.id', 'user_address.country_id')
            ->leftjoin('states', 'states.id', 'user_address.state_id')
            ->leftjoin('cities', 'cities.id', 'user_address.city_id')
            ->first();
        return $adress;
    }

    public static function get_user_default_address($userid)
    {

        $adress = UserAdress::where(['status' => 1, 'user_id' => $userid])->select('user_address.id','full_name','user_address.dial_code','phone', 'street', 'user_address.location', 'user_address.address_type', 'is_default', 'land_mark', 'building', 'latitude', 'longitude','apartment','user_address.state_id','user_address.city_id','states.name as state','cities.name as city')
         ->leftjoin('country', 'country.id', 'user_address.country_id')
            ->leftjoin('states', 'states.id', 'user_address.state_id')
            ->leftjoin('cities', 'cities.id', 'user_address.city_id')
            ->where('is_default', 1)->first();
        if (!$adress) {
            $adress = UserAdress::where(['status' => 1, 'user_id' => $userid])->select('user_address.id','full_name','user_address.dial_code','phone', 'street', 'user_address.location', 'user_address.address_type', 'is_default', 'land_mark', 'building', 'latitude', 'longitude','apartment','user_address.state_id','user_address.city_id','states.name as state','cities.name as city')
            ->leftjoin('country', 'country.id', 'user_address.country_id')
            ->leftjoin('states', 'states.id', 'user_address.state_id')
            ->leftjoin('cities', 'cities.id', 'user_address.city_id')
                ->orderBy('id', 'DESC')->first();
        }
        return $adress;
    }

}
