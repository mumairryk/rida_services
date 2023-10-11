<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    public static function avg_rating($where=[]){
        $ratingcount =  Rating::where($where)->get()->count();
        $ratingsum   =  Rating::where($where)->get()->sum('rating');  
        $avgrating   =  0;
        if($ratingcount != 0 && $ratingsum != 0)
        {
          $avgrating   =  $ratingsum/$ratingcount;  
        }
       return $avgrating;
    }
    public static function total_rating($where=[]){
        $ratingcount =  Rating::where($where)->get()->count();
        
       return $ratingcount;
    }
}
