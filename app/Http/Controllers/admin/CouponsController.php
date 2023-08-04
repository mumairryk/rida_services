<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\AmountType;
use App\Models\Categories;
use App\Models\ProductModel;
use App\Models\CouponCategory;
use Illuminate\Http\Request;
use Validator;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('coupon','View')) {
            abort(404);
        }
        $page_heading = "Coupons";
        $datamain = Coupons::orderBy('coupon_id', 'DESC')
        ->leftjoin('amount_type','amount_type.id','=','coupon.amount_type')
        ->get();
        return view('admin.coupons.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('coupon','Create')) {
            abort(404);
        }
        $category_ids = [];
        $page_heading = "Coupons";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $amounttype = AmountType::get();

        $categories = Categories::select('id','name')->orderBy('sort_order','asc')->where(['deleted'=>0,'active'=>1,])->get();

      
        return view("admin.coupons.create", compact('page_heading', 'mode', 'id', 'name', 'dial_code', 'active','prefix','amounttype','categories','category_ids'));
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
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'coupone_code'    => 'required',
            'coupone_amount'  => 'required',
            'amount_type'     => 'required',
            'expirydate'      => 'required',
            'startdate'       => 'required',
            'title'           => 'required',
            'description'     => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = Coupons::where(['coupon_code' => $request->coupone_code])->where('coupon_id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'coupon_code'      => $request->coupone_code,
                    'coupon_amount'    =>  $request->coupone_amount,
                    'amount_type'      =>  $request->amount_type,
                    'coupon_title'     => $request->title,
                    'coupon_description' => $request->description,
                    'coupon_status'    => $request->active,
                    'start_date'       => $request->startdate,
                    'coupon_end_date'  => $request->expirydate,
                    'applied_to'       => $request->applies_to,
                    'minimum_amount'   => $request->minimum_amount,
                    'coupon_usage_percoupon'   => $request->coupon_usage_percoupon,
                    'coupon_usage_peruser'     => $request->coupon_usage_peruser,
                ];
                
               
                $categories = $request->category_ids; 
                

                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    Coupons::where('coupon_id',$request->id)->update($ins);
                    CouponCategory::insertcategory($request->id,$categories);
                    $status = "1";
                    $message = "Coupon updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    Coupons::insert($ins);
                    $inid = Coupons::orderBy('coupon_id', 'desc')->get()->first();
                    CouponCategory::insertcategory($inid->coupon_id,$categories);
                    $status = "1";
                    $message = "Coupon added successfully";
                }
            } else {
                $status = "0";
                $message = "Coupon code should be unique";
                $errors['coupone_code'] = $request->coupone_code . " already added";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('coupon','Edit')) {
            abort(404);
        }
        $category_ids = [];
        $amounttype = AmountType::get();
        $datamain = Coupons::where('coupon_id',$id)->first();
        if ($datamain) {
            $page_heading = "Coupon";
            $mode = "edit";
            $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";

        $categories = Categories::select('id','name')->orderBy('sort_order','asc')->where(['deleted'=>0,'active'=>1,])->get();

        $product_categories = CouponCategory::where('coupon_id',$id)->get()->toArray();
        $category_ids       = array_column($product_categories,'category_id');


        $id = "";
            return view("admin.coupons.create", compact('page_heading', 'datamain','id','amounttype','categories','category_ids'));
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
        $datamain = Coupons::where('coupon_id',$id)->first();
        if ($datamain) {
            Coupons::where('coupon_id',$id)->delete();
            $status = "1";
            $message = "Coupon removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}
