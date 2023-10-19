<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class EnquiryDetails extends Model
{

    protected $table = 'enquiry_details';

    function question(){
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
