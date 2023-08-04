<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollow extends Model
{
    use HasFactory;
    protected $appends = ['followed_from'];
    public function getFollowedFromAttribute($value){
     return get_date_in_timezone($value,'Y-m-d H:i:s');
    }
    public function followed(){
      return $this->belongsTo('App\Models\User', 'follow_user_id', 'id');
    }
    public function follower(){
      return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public static function get_my_followers( $user_id,$search_text=''){
        $list = UserFollow::join('users as followers','followers.id','=','user_id')
        ->select(['followers.name','followers.user_image','followers.id as user_id','followers.firebase_user_key','followers.user_name','user_follows.id as follow_id'])
        ->addSelect(\DB::Raw("(select count(id) from user_follows where user_id='".$user_id."' and follow_user_id=followers.id) as is_folowed_by_me"))
        ->where(['follow_user_id'=>$user_id])->orderBy('user_follows.id','desc');
        if($search_text != ''){
            $list = $list->where(function ($query) use ($search_text) {
                $query->where('followers.name', 'ILIKE', '%'.$search_text . '%');
                $query->orWhere('followers.user_name', 'ILIKE', '%'.$search_text . '%');
            });
        }

        return $list;
    }
    public static function my_following_list( $user_id,$search_text=''){
        $list = UserFollow::join('users as following','following.id','=','follow_user_id')
        ->select(['following.name','following.user_image','following.id as user_id','following.firebase_user_key','following.user_name','user_follows.id as follow_id'])
        ->addSelect(\DB::Raw("(select count(id) from user_follows where follow_user_id='".$user_id."' and user_id=following.id) as is_folowed_by_him"))
        ->where(['user_id'=>$user_id])->orderBy('user_follows.id','desc');
        if($search_text != ''){
            $list = $list->where(function ($query) use ($search_text) {
                $query->where('following.name', 'ILIKE', '%'.$search_text . '%');
                $query->orWhere('following.user_name', 'ILIKE', '%'.$search_text . '%');
            });
        }
        return $list;
    }

    public static function get_others_followers( $user_id, $loged_user_id='',$search_text='' ){
        $list = UserFollow::join('users as followers','followers.id','=','user_id')
        ->select(['followers.name','followers.user_image','followers.id as user_id','followers.firebase_user_key','followers.user_name','user_follows.id as follow_id'])
        ->addSelect(\DB::Raw("(select count(id) from user_follows where user_id='".$loged_user_id."' and follow_user_id=followers.id) as is_folowed_by_me"))
        ->where(['follow_user_id'=>$user_id])->orderBy('user_follows.id','desc');
        if($search_text != ''){
            $list = $list->where(function ($query) use ($search_text) {
                $query->where('followers.name', 'ILIKE', '%'.$search_text . '%');
                $query->orWhere('followers.user_name', 'ILIKE', '%'.$search_text . '%');
            });
        }
        return $list;
    }
    public static function others_following_list( $user_id, $loged_user_id='',$search_text=''){
        $list = UserFollow::join('users as following','following.id','=','follow_user_id')
        ->select(['following.name','following.user_image','following.id as user_id','following.firebase_user_key','following.user_name','user_follows.id as follow_id'])
        ->addSelect(\DB::Raw("(select count(id) from user_follows where user_id='".$loged_user_id."' and follow_user_id=following.id) as is_folowed_by_me"))
        ->where(['user_id'=>$user_id])->orderBy('user_follows.id','desc');
        if($search_text != ''){
            $list = $list->where(function ($query) use ($search_text) {
                $query->where('following.name', 'ILIKE', '%'.$search_text . '%');
                $query->orWhere('following.user_name', 'ILIKE', '%'.$search_text . '%');
            });
        }
        return $list;
    }
}
