<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enquiry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('enquiry','View')) {
            abort(404);
        }
        $page_heading = "Enquiry";
        $filter = [];
        $params = [];
        $params['search_key'] = $_GET['search_key'] ?? '';
        $params['question_for'] = $_GET['question_for'] ?? '';
        $params['customer'] = $_GET['customer'] ?? '';
        $search_key = $params['search_key'];
        $question_for = $params['question_for'];
        $customer = $params['customer'];
        if(!empty($question_for))
        {
         $page_heading = "Enquiry - ".question_for($question_for);   
        }
        $list = Enquiry::select('enquiry.*','users.name','users.phone','users.dial_code')->leftjoin('users','users.id','=','user_id');
        if(!empty($question_for))
        {
        $list = $list->where('type',$question_for);     
        }
        if(!empty($customer))
        {
        $list = $list->where('user_id',$customer);     
        }
        $list = $list->paginate(10);
        return view("admin.enquiry.list", compact("page_heading", "list", "search_key","question_for",'customer'));
    }
    public function create(Request $request)
    {
        $params['question_for'] = $_GET['question_for'] ?? '';
        $options = [];
        if (!check_permission('questions','Create')) {
            abort(404);
        }
        $question_for = $params['question_for'];
            $page_heading = "Create Question";
            return view('admin.questions.create', compact('page_heading','options','question_for'));
       

    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $errors = '';
            $validator = Validator::make($request->all(),
                [
                    'question' => 'required',
                    'question_for' => 'required',
                    'answer_type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $ins['question'] = $request->question;
                $ins['active'] = $request->active;
                $ins['question_for'] = $request->question_for;
                $ins['answer_type'] = $request->answer_type;
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                if(!$request->id)
                {
                    $insert = Question::create($ins)->id;
                    $status = "1";
                    $message = "Question created";
                    $errors = '';
                    $inid = $insert;
                   
                }
                else
                {
                    $inid = $request->id;
                    Question::where('id',$request->id)->update($ins);
                    $status = "1";
                    $message = "Question updated";
                }

                if(!empty($inid))
                {  
                     $options = $request->option;
                     QuestionOptions::where('question',$inid)->delete();
                     if($request->answer_type == 3 || $request->answer_type == 4)
                     { 
                         foreach ($options as $key => $value) {
                             $inoptions = new QuestionOptions;
                             $inoptions->question = $inid;
                             $inoptions->options = $value;
                             $inoptions->save();
                         }
                        
                     }
                }
                
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
        } 

    }
    public function edit($id = '')
    {
        if (!check_permission('questions','Edit')) {
            abort(404);
        }
        $question_for = '';
        $datamain = Question::find($id);
        $options = QuestionOptions::where('question',$id)->get();
        if ($datamain) {
            $page_heading = "Edit Question";
            return view('admin.questions.create', compact('page_heading', 'datamain','options','question_for'));
        } else {
            abort(404);
        }
    }

    public function details($id = '')
    {
        if (!check_permission('questions','Edit')) {
            abort(404);
        }
        $question_for = '';
        $datamain = Enquiry::with('customer','enquiery_details')->find($id);
        
        if ($datamain) {
            $page_heading = "Enquiry - ".question_for($datamain->type);
            return view('admin.enquiry.details', compact('page_heading', 'datamain','question_for'));
        } else {
            abort(404);
        }
    }

    
    public function destroy($id = '')
    {
        Question::where('id', $id)->delete();
        $status = "1";
        $message = "Question removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function sort(Request $request)
    {
        $params['question_for'] = $_GET['question_for'] ?? '';
        $question_for = $params['question_for'];
        if ($request->ajax()) {
            $status = 0;
            $message = '';

            $items = $request->items;
            $items = explode(",", $items);
            $sorted = Question::sort_item($items);
            if ($sorted) {
                $status = 1;
            }

            echo json_encode(['status' => $status, 'message' => $message]);

        } else {
            $page_heading = "Sort Question";
            if(!empty($question_for))
            {
           $page_heading = "Questions - ".question_for($question_for);   
           }

            $list = Question::select('id','question as name')->where(['question_for' => $question_for])->orderBy('sort_order', 'asc')->get();
            $back = url("admin/questions?question_for=".$question_for);
            return view("admin.sort", compact('page_heading', 'list','back'));
        }
    }

}
