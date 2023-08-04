<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostFiles extends Model
{
    use HasFactory;
    protected $appends = ['thumb_image_url','mp4_cdn_url'];
    public function getThumbImageUrlAttribute(){
     return get_uploaded_image_url($this->thumb_image,'post_image_upload_dir');
    }
    public function getHaveHlsUrlAttribute($value){
     return (string)$value;
    }
    public function getMp4CdnUrlAttribute(){
        $urlpart = explode("/",$this->url);
        $urls = array_reverse($urlpart);
     return (string)"https://d3k2qvqsrjpakn.cloudfront.net/moda/public/".config('global.post_image_upload_dir').$urls[0];
    }
    public function getHlsUrlAttribute($value){
     return (string)$value;
    }
    public function getHlsCdnUrlAttribute($value){
     return (string)$value;
    }
    public function getUrlAttribute($value){
        return get_uploaded_image_url($value,'post_image_upload_dir');
    }
}
