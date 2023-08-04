<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Validator;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('account_type', 'View')) {
            abort(404);
        }
        $page_heading = "Account Type";
        $accountTypes = AccountType::where(['deleted' => '0'])
            ->orderBy('id', 'desc')->get();
        return view('admin.account_type.list', compact('page_heading', 'accountTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('account_type', 'Create')) {
            abort(404);
        }
        $page_heading = "Account Type";
        $mode = "create";
        $id = "";
        $accountType = "";
        $name = "";
        $description = "";
        return view("admin.account_type.create", compact('page_heading', 'accountType', 'description', 'id', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = [];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();

            $luser_name = strtolower($request->name);
            $check_user_name_exist = AccountType::whereRaw("LOWER(name) = '$luser_name'")->where('id', '!=', $request->id)->get()->toArray();
            if ($check_user_name_exist) {
                $status = "0";
                $message = "name should be unique";
                $errors['name'] = "Already exist";
                echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
                die();
            }

            $ins = [
                'name' => $request->name,
                'description' => $request->description ?? '',
            ];

            if ($request->id != "") {
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                $user = AccountType::find($request->id);
                $user->update($ins);

                $status = "1";
                $message = "Account Type updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $account_tye_id = AccountType::create($ins)->id;

                $status = "1";
                $message = "Account Type added successfully";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('account_type', 'Edit')) {
            abort(404);
        }
        $page_heading = "Edit Account Type";
        $accountType = AccountType::find($id);
        if (!$accountType) {
            abort(404);
        }

        if ($accountType) {
            $name = $accountType->name;
            $description  = $accountType->description;
            return view("admin.account_type.create", compact('page_heading', 'name', 'description', 'id'));
        } else {
            abort(404);
        }
    }


    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $datatb = AccountType::find($id);
        if ($datatb) {
            $datatb->deleted = 1;
            $datatb->save();
            $status = "1";
            $message = "Account Type removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}