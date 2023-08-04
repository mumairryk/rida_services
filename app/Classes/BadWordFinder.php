<?php
namespace App\Classes;
use App\Models\Post;
use App\Models\User;
use App\Models\BadWords;

class BadWordFinder{
    private $apiKey;
    private $url;
    private $host;
    public $bad_words;
    public $is_bad_word_exist;

    public function __construct()
    {
        $this->apiKey = 'c2ca99eeebmsh883184a3e66a617p19a0a6jsn642a78549648';
        $this->url = 'https://neutrinoapi-bad-word-filter.p.rapidapi.com/bad-word-filter';
        $this->host = 'neutrinoapi-bad-word-filter.p.rapidapi.com';
        $this->is_bad_word_exist = 0;
    }
    function check_word($content=''){
        //first check in local
        $local_check = $this->check_in_local($content);
        if($local_check == 1 ){
            $this->is_bad_word_exist = 1;
        }else{
            $check_live = $this->check_live($content);
            if($check_live == 1){
                $this->is_bad_word_exist = 1;
            }
        }
        return $this->is_bad_word_exist;
    }

    function insert_local($words){
        $ins = [];
        if(is_array($words)){
            foreach($words as $word){
                $ins[] = ['word'=>$word,'created_at'=>gmdate('Y-m-d H:i:s'),'updated_at'=>gmdate('Y-m-d H:i:s')];
            }
        }else if(is_string($words)){
            $ins[] = ['word'=>$words,'created_at'=>gmdate('Y-m-d H:i:s'),'updated_at'=>gmdate('Y-m-d H:i:s')];
        }
        if(!empty($ins)){
            BadWords::insert($ins);
        }
    }

    function check_live($content){
        $curl = curl_init();
        curl_setopt_array($curl, [
        	CURLOPT_URL => $this->url,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 30,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "POST",
        	CURLOPT_POSTFIELDS => "content=".$content."&censor-character=*",
        	CURLOPT_HTTPHEADER => [
        		"X-RapidAPI-Host: ".$this->host,
        		"X-RapidAPI-Key: ".$this->apiKey,
        		"content-type: application/x-www-form-urlencoded"
        	],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        	return 0;
        } else {
            $output = json_decode($response);
        	if($output->{"is-bad"} == true){
                $this->bad_words = $output->{"bad-words-list"};
                $this->insert_local($this->bad_words);
                return 1;
            }else{
                return 0;
            }
        }
    }

    function check_in_local($content=''){
        if($content != ''){
            $words = explode(" ",strtolower($content));
            $check_exist =  BadWords::whereIn('word',$words)->get();
            if($check_exist->count() > 0){
                $words  = $check_exist->toArray();
                $items  = array_column($words,'word');
                $this->bad_words = $items;
                return 1;
            }else{
                return 0;
            }
        }
    }
}
