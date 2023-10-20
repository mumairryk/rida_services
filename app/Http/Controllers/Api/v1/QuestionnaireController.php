<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOptions;
use App\Models\User;
use App\Models\UserAdress;
use App\Models\OrderStatusHistroy;
use App\Models\PaymentReport;
use App\Models\Enquiry;
use App\Models\EnquiryDetails;
use DB;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;

class QuestionnaireController extends Controller
{
    public $lang = '';
    public function __construct(Database $database,Request $request)
    {
        $this->database = $database;
        if(isset($request->lang)) {
            \App::setLocale($request->lang);
        }
        $this->lang = \App::getLocale();
    }
    private function validateAccesToken($access_token)
    {

        $user = User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Invalid login',
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user != null) { //$user->active == 1
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }

    public function questionnaire(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $type = $request->type;

            $datamain = Question::select('id','question','active','question_for','answer_type','placeholder')->where(['active'=>1,'question_for'=>$type])->orderBy('sort_order','asc')->get();

            foreach ($datamain as $key => $value) {
                $datamain[$key]->answer_type_text = answer_type($value->answer_type);
                $datamain[$key]->options = QuestionOptions::select('question','options')->where('question',$value->id)->get();
            }

            $o_data['list'] = convert_all_elements_to_string($datamain);
          
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }

    public function enquiry(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'type' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $type = $request->type;

            $datainput = json_decode($request->answer,true);

               $datains = new Enquiry;
               $datains->user_id = $user_id;
               $datains->type = $request->type;
               $datains->status = 1;
               $datains->created_at = gmdate('Y-m-d H:i:s');
               $datains->updated_at = gmdate('Y-m-d H:i:s');
               $datains->save();

            foreach ($datainput as $key => $value) {
               $datains1 = new EnquiryDetails;
               $datains1->enquiry_id = $datains->id;
               $datains1->question_id = $value['question_id'];
               $datains1->answers = $value['answers'];
               $datains1->created_at = gmdate('Y-m-d H:i:s');
               $datains1->updated_at = gmdate('Y-m-d H:i:s');
               $datains1->save();
            }
             $users = User::find($user_id);
             $title = "Enquiry ID: ".$datains->id;
                $description = "Your enquiry sent successfully";
                $notification_id = time();
                $ntype = 'enquiry_sent';
                if (!empty($users->firebase_user_key)) {
                    $notification_data["Nottifications/" . $users->firebase_user_key . "/" . $notification_id] = [
                        "title" => $title,
                        "description" => $description,
                        "notificationType" => $ntype,
                        'createdDate'     => gmdate("d-m-Y H:i:s", $notification_id),
                        "orderId" => (string) $datains->id,
                        "url" => "",
                        "imageURL" => '',
                        "read" => "0",
                        "seen" => "0",
                    ];
                    $this->database->getReference()->update($notification_data);
                }

                if (!empty($users->user_device_token)) {
                    send_single_notification($users->user_device_token, [
                        "title" => $title,
                        "body" => $description,
                        "icon" => 'myicon',
                        "sound" => 'default',
                        "click_action" => "EcomNotification"],
                        ["type" => $ntype,
                            "notificationID" => $notification_id,
                            "orderId" => (string) $datains->id,
                            "imageURL" => "",
                        ]);
                }

            $status = "1";
            $message = "Enquiry sent successfully";
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }

    public function my_enquiries(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $type = $request->type;

            $o_data['list'] = Enquiry::where('user_id',$user_id)->orderBy('id','desc')->paginate(10)->items();
            foreach ($o_data['list'] as $key => $value) {
                $o_data['list'][$key]->type_text = question_for($value->type);
                $o_data['list'][$key]->date = web_date_in_timezone($value->created_at,'d F Y');
                $o_data['list'][$key]->details = EnquiryDetails::select('question','answers')->where('enquiry_id',$value->id)->leftjoin('question','question.id','=','enquiry_details.question_id')->get();
            }

            $status = "1";
            $message = "Enquiry List";
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }
    
}
