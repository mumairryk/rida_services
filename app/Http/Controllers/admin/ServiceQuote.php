<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceQuotes;
use App\Models\ServiceFoods;
use Illuminate\Http\Request;
use Validator;

class ServiceQuote extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('service_quotes', 'View')) {
            abort(404);
        }
        $service = $_GET['service'] ?? '';
        $page_heading = $service == 5 ? 'Service Bookings' :"Service Quotes";
        $datamain = ServiceQuotes::select('service_quotes.*','users.name as customer','services.name as service')->join('users', 'users.id', '=', 'service_quotes.user_id')->join('services', 'services.id', '=', 'service_quotes.service_id')->where(['service_quotes.deleted' => 0])->orderBy('service_quotes.created_at', 'desc');
        if($service){
            $datamain = $datamain->where('service_quotes.service_id',$service);
        }
        $datamain = $datamain->get();

        return view('admin.service_quotes.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     if (!check_permission('service_quotes', 'Create')) {
    //         abort(404);
    //     }
    //     $page_heading = "Service Quotes";
    //     $mode = "create";
    //     $id = "";
    //     return view("admin.service_quotes.create", compact('page_heading', 'mode', 'id'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $status = "0";
    //     $message = "";
    //     $o_data = [];
    //     $errors = [];
    //     $redirectUrl = '';


    //     $rules = [
    //         'name' => 'required',
            
    //     ];
        

    //     $validator = Validator::make(
    //         $request->all(),
    //         $rules,
    //         [
    //             'name.required' => 'Name is required',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         $status = "0";
    //         $message = "Validation error occured";
    //         foreach ($validator->messages()->toArray() as $key => $row) {
    //             $errors[0][$key] = $row[0];
    //         }
    //     }  else {
    //         $input = $request->all();

            
    //         $ins = [
    //             'name' => $request->name,
    //             'updated_at' => gmdate('Y-m-d H:i:s'),
    //             'active' => $request->active,
    //         ];

    //         if ($request->id != "") {
    //             $service_quotes = ServiceQuotes::find($request->id);
    //             $service_quotes->update($ins);
    //             $status = "1";
    //             $message = "Service Quote updated succesfully";
    //         } else {
    //             $ins['created_at'] = gmdate('Y-m-d H:i:s');
    //             ServiceQuotes::create($ins);
    //             $status = "1";
    //             $message = "Service Quote added successfully";
    //         }

    //     }
    //     echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id='')
    {
        if (!check_permission('service_quotes', 'Details')) {
            abort(404);
        }
        $page_heading = "Service Quote";
        $datamain = ServiceQuotes::select('service_quotes.*','users.name as customer','services.name as service')->join('users', 'users.id', '=', 'service_quotes.user_id')->join('services', 'services.id', '=', 'service_quotes.service_id')->where(['service_quotes.deleted' => 0,'service_quotes.id'=>$id])->with(['pets','appointment_types','feeding_schedules','doctor','groomer','play_staff','grooming_type'])->first();
        if (!$datamain) {
            abort(404);
        }
        $seleted_foods = ServiceFoods:: select('name')->join('foods','foods.id','service_foods.food_id')->where('service_id',$id)->get()->toArray();
        $seleted_foods = implode(", ",array_column($seleted_foods,'name'));
        // dd($datamain);
        return view('admin.service_quotes.details', compact('page_heading', 'datamain','seleted_foods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('service_quotes', 'Edit')) {
            abort(404);
        }
        $datamain = ServiceQuotes::find($id);
        if ($datamain) {
            $page_heading = "Service Quotes ";
            $mode = "edit";
            $id = $datamain->id;

            return view("admin.service_quotes.create", compact('page_heading', 'datamain', 'mode', 'id'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $category = ServiceQuotes::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Service Quote removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (ServiceQuotes::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function change_quote_status(Request $request)
    {
        $status = "0";
        $message = "";
        if($request->detailsid && $request->statusid){
            if($request->statusid==1){
                
                $update['status'] = config('global.service_quote_sent');
                $update['quote_price'] = $request->quote_price;

                if ($file = $request->file("quote_doc")) {
                    $file_name = time() . uniqid() . "_doc." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.service_image_upload_dir'), $file_name, config('global.upload_bucket'));
                    $update['quote_document'] = $file_name;
                }
            }else{
                $update['status'] = config('global.service_status_rejected');
                $update['reject_reason'] = $request->reject_reason;
            }
            if (ServiceQuotes::where('id', $request->detailsid)->update($update)) {
                $status = "1";
                $message = "Successfully updated";
            } else {
                $message = "Something went wrong";
            }
        }else{
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    
}
