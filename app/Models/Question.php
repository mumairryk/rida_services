<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Question extends Model
{
    //
    protected $table = "question";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $guarded;


   
    public static function get_list($where=[],$params=[]){
        $faq = Question::where($where)->orderBy('created_at','desc');  
        if( !empty($params) ){
            if(isset($params['search_key']) && $params['search_key'] != ''){
                $faq->Where('question','ilike','%'.$params['search_key'].'%');
            }
            if(isset($params['question_for']) && $params['question_for'] != ''){
                $faq->Where('question_for',$params['question_for']);
            }
        }
        return $faq;
    } 
    public static function sort_item($item=[]){
        if( !empty($item) ){
            DB::beginTransaction();
            try {
                    $i=0;
                    foreach( $item as $key ){
                        Question::where('id', $key)
                            ->update(['sort_order' => $i]);
                        $i++;
                    }
                    DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
        }else{
            return 0;
        }
    }
    
}
