<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserFavorite extends Model
{
    use HasFactory;
    public static function my_fav_list($user_id, $sort_user = '')
    {
        if (!$sort_user) {
            $list = UserFavorite::join('users as fav', 'fav.id', '=', 'fav_user_id')->distinct('fav_user_id')
                ->select(['fav.name', 'fav.user_image', 'fav.id as user_id', 'fav.firebase_user_key', 'fav.user_name', 'user_favorites.id as fav_id', 'fav.user_name', 'fav.user_name', 'lattitude', 'longitude'])->leftjoin('user_locations', 'user_locations.user_id', '=', 'fav_user_id');
            $list = $list->where(['user_favorites.user_id' => $user_id])->orderBy('fav_user_id')->orderBy('user_favorites.id', 'desc');
            return $list;
        } else {
            $list = \App\Models\User::select(['users.name', 'users.user_image', 'users.id as user_id', 'users.firebase_user_key', 'users.user_name', 'lattitude', 'longitude'])->leftjoin('user_locations', 'user_locations.user_id', '=', 'users.id')->where('users.id', $sort_user);
            return $list;
        }

    }
}
