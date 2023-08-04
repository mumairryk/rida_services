<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserLocations extends Model
{
    use HasFactory;
    protected $table = "user_locations";
    protected $guarded = [];
    public static function fetch_nearest_users($lat='',$lon='',$login_user_id=0){
        $limit_distance = config('global.limit_distance');
        $list = UserLocations::select(['user_id',
        \DB::raw("6371 * acos(cos(radians(" . $lat . "))
                            * cos(radians(lattitude::float))
                            * cos(radians(longitude::float) - radians(" . $lon . "))
                            + sin(radians(" .$lat. "))
                            * sin(radians(lattitude::float))) AS distance")
        ]);
        //$list = $list->get();
        $sql = $list->toSql();

        // $data = DB::table(DB::raw('(' . $sql . ') as sub'))
        //         ->select(DB::raw("*"));
        // $data=$data->where('distance','<=',$limit_distance);

        $users = User::join('user_locations','user_locations.user_id','=','users.id')
        ->distinct('user_locations.user_id')
        ->whereIn('users.id',function($query) use($sql,$limit_distance){
            $query->select('user_id')->from(DB::raw('(' . $sql . ') as sub'))->where('distance','<=',$limit_distance);

        })
        ->orderBy('user_locations.user_id','desc')
        ->orderBy('user_locations.id','desc')
        ->select(['user_id','user_image','user_name','firebase_user_key','is_private_profile','lattitude','longitude','dial_code','phone'])
        ->addSelect(['followed_by_user' => UserFollow::selectRaw("count(id)")
              ->where('user_id', $login_user_id)
              ->where('request_accept_status',1)
              ->whereColumn('follow_user_id', 'users.id')
          ])
        ->get();
        return $users;


    }

    public static function get_cross_by_users($lattitude='',$longitude='',$user_id=0,$offset=0,$limit=10,$filters=[]){
        $lattitude = round($lattitude,5);
        $longitude = round($longitude,5);
        $limit_distance =  $filters['distance']??config('global.limit_distance_crossby');
        $list = UserLocations::select(['user_id',
        \DB::raw("6371 * acos(cos(radians(" . $lattitude . "))
                            * cos(radians(lattitude::double precision))
                            * cos(radians(longitude::double precision) - radians(" . $longitude . "))
                            + sin(radians(" .$lattitude. "))
                            * sin(radians(lattitude::double precision))) AS distance")
        ]);
        //$list = $list->get();
        $sql = $list->toSql();
        $user = User::join('user_locations',function($query) {
            	$query->on('user_locations.user_id','=','users.id')
            	->whereRaw('user_locations.id IN (select MAX(a2.id) from user_locations as a2 where a2.user_id=users.id)');
            	})
            	->distinct('user_locations.user_id')
            	->whereIn('users.id',function($query) use($sql,$limit_distance){
                    $query->select('user_id')->from(DB::raw('(' . $sql . ') as sub'))->where('distance','<=',$limit_distance);

                })
                ->orderBy('user_locations.user_id')
                ->orderBy('user_locations.id','desc')
                //->where('user_locations.lattitude','=',$lattitude)
                //->where('user_locations.longitude','=',$longitude)
                ->where('user_id','!=',$user_id)
                ->select(['user_id','name','user_image','user_name','firebase_user_key','is_private_profile','lattitude','longitude','dial_code','phone','user_locations.created_at as visited_date_time'])
                ->addSelect(['followed_by_user' => DB::raw(
                    "
          CASE
            WHEN
              (select count(id) from user_follows where user_id='" . $user_id . "' and follow_user_id=user_locations.user_id) >= 1 and
              (select count(id) from user_follows where user_id=user_locations.user_id and follow_user_id='" . $user_id . "') >= 1
            THEN '1'
            WHEN
              (select count(id) from user_follows where user_id='" . $user_id . "' and follow_user_id=user_locations.user_id) = 0 and
              (select count(id) from user_follows where user_id=user_locations.user_id and follow_user_id='" . $user_id . "') >= 1
            THEN '3'
            WHEN
              (select count(id) from user_follows where user_id='" . $user_id . "' and follow_user_id=user_locations.user_id) >= 1 and
              (select count(id) from user_follows where user_id=user_locations.user_id and follow_user_id='" . $user_id . "') = 0
            THEN '2'
            WHEN
              (select count(id) from user_follows where user_id='" . $user_id . "' and follow_user_id=user_locations.user_id) = 0 and
              (select count(id) from user_follows where user_id=user_locations.user_id and follow_user_id='" . $user_id . "') = 0
            THEN '0'
          END as followed_by_user


          "
                )
                  ]);
        //  if(isset($filters['distance']) && $filters['distance'] != ''){
        //      $user=$user->where('distance','<=',$filters['distance']);
        //  }
         if(isset($filters['search_key']) && $filters['search_key'] != ''){
             $search_text=$filters['search_key'];
             $user=$user->where(function($query) use ($search_text){
                 $query->where('users.name', 'ILIKE', $search_text.'%');
                 $query->orWhere('users.user_name', 'ILIKE', $search_text.'%');
             });
         }
         $user = $user->skip($offset)->take($limit)->get();
        return $user;
    }
}
