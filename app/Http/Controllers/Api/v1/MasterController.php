<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomRequestModel;
use App\Models\OrderServiceItemsModel;
use DB;
use Validator;
use App\Models\ProjectPurpose;
use App\Classes\FaceReg;

class MasterController extends Controller
{
   public function getProjectPurpose(){
        $datamain = ProjectPurpose::get();
        $status = 1;
        $message = "project purpose data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => $datamain], 200);
   }
    
}
