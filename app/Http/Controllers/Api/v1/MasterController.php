<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomRequestModel;
use App\Models\OrderServiceItemsModel;
use DB;
use Validator;
use App\Models\ProjectPurpose;
use App\Models\Room;
use App\Models\SquareFootage;
use App\Models\TypeofProperty;
use App\Classes\FaceReg;
use App\Models\Divisions;

class MasterController extends Controller
{
   public function getProjectPurpose(){
        $datamain = ProjectPurpose::get();
        $status = 1;
        $message = "project purpose data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => $datamain], 200);
   }

   public function getDivisions(){
        $datamain = Divisions::where(['deleted' => 0,'active'=>1])->get();
        $status = 1;
        $message = "Divisions data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => convert_all_elements_to_string($datamain)], 200);
   }

   public function getRoomType(){
        $datamain = Room::get();
        $status = 1;
        $message = "Room Type data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => $datamain], 200);

   }

   public function getSquareFootage(){
        $datamain = SquareFootage::get();
        $status = 1;
        $message = "Square Footage data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => $datamain], 200);
   }

   public function getTypeOfProperty(){
        $datamain = TypeofProperty::get();
        $status = 1;
        $message = "Type of Property data fetched successfully!";
        return response()->json(['status' => $status, 'message' => $message, 'Data' => $datamain], 200);
   }
    
}
