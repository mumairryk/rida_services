<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('questions','View')) {
            abort(404);
        }
        $page_heading = "Questions";
        $filter = [];
        $params = [];
        $params['search_key'] = $_GET['search_key'] ?? '';
        $search_key = $params['search_key'];
        $list = Question::get_list($filter, $params)->paginate(10);
        return view("admin.questions.list", compact("page_heading", "list", "search_key"));
    }
    public function create(Request $request)
    {
        if (!check_permission('questions','Create')) {
            abort(404);
        }
        
            $page_heading = "Create Question";
            return view('admin.questions.create', compact('page_heading'));
       

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
                if()
                if (Question::insert($ins)) {
                    $status = "1";
                    $message = "Question created";
                    $errors = '';
                } else {
                    $status = "0";
                    $message = "Something went wrong";
                    $errors = '';
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
        $datamain = Question::find($id);
        if ($datamain) {
            $page_heading = "Edit Question";
            return view('admin.questions.create', compact('page_heading', 'datamain'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = '';
        $validator = Validator::make($request->all(),
            [
                'question' => 'required',
                'answer' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $ins['active'] = $request->active;
            $ins['title'] = $request->question;
            $ins['description'] = $request->answer;
            $ins['updated_at'] = gmdate('Y-m-d H:i:s');
            $ins['updated_by'] = session("user_id");
            
            if (FaqModel::where('id', $request->id)->update($ins)) {

                $status = "1";
                $message = "Question updated";
                $errors = '';
            } else {
                $status = "0";
                $message = "Something went wrong";
                $errors = '';
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
    }
    public function delete($id = '')
    {
        FaqModel::where('id', $id)->delete();
        $status = "1";
        $message = "Question removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function sort(Request $request)
    {
        if ($request->ajax()) {
            $status = 0;
            $message = '';

            $items = $request->items;
            $items = explode(",", $items);
            $sorted = Categories::sort_item($items);
            if ($sorted) {
                $status = 1;
            }

            echo json_encode(['status' => $status, 'message' => $message]);

        } else {
            $page_heading = "Sort Categories";

            $list = Categories::where(['deleted' => 0])->orderBy('sort_order', 'asc')->get();
            $back = url("admin/category");
            return view("admin.sort", compact('page_heading', 'list','back'));
        }
    }

}
