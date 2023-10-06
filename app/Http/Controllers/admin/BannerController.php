<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerModel;
use App\Models\Categories;
use App\Models\Divisions;
use Illuminate\Http\Request;
use Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_heading = "App Banners";
        $filter = [];
        $params = [];
        $params['search_key'] = $_GET['search_key'] ?? '';
        $search_key = $params['search_key'];
        $list = BannerModel::get_banners_list($filter, $params)->paginate(10);

        return view("admin.banner.list", compact("page_heading", "list", "search_key"));
    }
    // public function web_banner()
    // {
    //     $page_heading = "Web Banners";
    //     $filter = [];
    //     $params = [];
    //     $params['search_key'] = $_GET['search_key'] ?? '';
    //     $search_key = $params['search_key'];
    //     $list = WebBannerModel::get_banners_list($filter, $params)->paginate(10);
    //     return view("admin.banner.web_banner", compact("page_heading", "list", "search_key"));
    // }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $errors = '';
            $validator = Validator::make($request->all(),
                [
                    'banner' => 'required|image',
                    'type' => 'required',
                ],
                [
                    'banner.required' => 'Banner required',
                    'type.required' => 'Banner Type required',
                    'banner.image' => 'should be in image format (.jpg,.jpeg,.png)',
                ]
            );
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $ins['active'] = $request->active;
                $ins['type'] = $request->type;
                $ins['banner_title'] = '';
                $ins['category_id'] = $request->category_id ?? 0;
                $ins['division_id'] = $request->division_id ?? 0;
                $ins['product_id'] = $request->product_id ?? 0;
                
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $ins['created_by'] = session("user_id");
                if ($file = $request->file("banner")) {
                    $dir = config('global.upload_path') . config('global.banner_image_upload_dir');
                    $file_name = time() . uniqid() . "_banner." . $file->getClientOriginalExtension();
                    $file->move($dir, $file_name);
                    $ins['banner_image'] = $file_name;
                }
                if (BannerModel::insert($ins)) {

                    $status = "1";
                    $message = "Banner created";
                    $errors = '';
                } else {
                    $status = "0";
                    $message = "Something went wrong";
                    $errors = '';
                }
            }
            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
        } else {
            $page_heading = "Create App Banner";
            $categories = Categories::where(['deleted'=>0,'active'=>1])->get();
            $divisions = Divisions::where(['deleted' => 0,'active'=>1])->get();
            return view('admin.banner.create', compact('page_heading','categories','divisions'));
        }

    }
    // public function create_web_banner(Request $request)
    // {
    //     if ($request->isMethod('post')) {
    //         $status = "0";
    //         $message = "";
    //         $errors = '';
    //         $validator = Validator::make($request->all(),
    //             [
    //                 'banner' => 'required|image',
    //             ],
    //             [
    //                 'banner.required' => 'Banner required',
    //                 'banner.image' => 'should be in image format (.jpg,.jpeg,.png)',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             $status = "0";
    //             $message = "Validation error occured";
    //             $errors = $validator->messages();
    //         } else {
    //             $ins['active'] = $request->active;
    //             $ins['banner_title_1'] = $request->banner_title_1;
    //             $ins['banner_title_2'] = $request->banner_title_2;
    //             $ins['banner_title_3'] = $request->banner_title_3;
    //             $ins['banner_title_4'] = $request->banner_title_4;
    //             $ins['created_on'] = gmdate('Y-m-d H:i:s');
    //             $ins['created_by'] = session("user_id");
    //             if ($file = $request->file("banner")) {
    //                 if(isset($request->cropped_upload_image) && $request->cropped_upload_image){
    //                     $image_parts = explode(";base64,", $request->cropped_upload_image);
    //                     $image_type_aux = explode("image/", $image_parts[0]);
    //                     $image_type = $image_type_aux[1];
    //                     $image_base64 = base64_decode($image_parts[1]);
    //                     $imageName = uniqid() .time(). '.'.$image_type;
    //                     $path = \Storage::disk('s3')->put(config('global.banner_image_upload_dir').$imageName, $image_base64);
    //                     $path = \Storage::disk('s3')->url($path);
    //                     $ins['banner_image'] = $imageName;
    //                 }else{
    //                     $dir = config('global.upload_path') . "/" . config('global.banner_image_upload_dir');
    //                     $file_name = time() . $file->getClientOriginalName();
    //                     //$file->move($dir, $file_name);
    //                     $file->storeAs(config('global.banner_image_upload_dir'),$file_name,'s3');
    //                     $ins['banner_image'] = $file_name;
    //                 }
    //             }
    //             if (WebBannerModel::insert($ins)) {

    //                 $status = "1";
    //                 $message = "Banner created";
    //                 $errors = '';
    //             } else {
    //                 $status = "0";
    //                 $message = "Something went wrong";
    //                 $errors = '';
    //             }
    //         }
    //         echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
    //     } else {
    //         $page_heading = "Create Web Banner";
    //         return view('admin.banner.create_web_banner', compact('page_heading'));
    //     }

    // }
    public function edit($id = '')
    {
        $banner = BannerModel::find($id);
        if ($banner) {
            $page_heading = "Edit App Banner";
            $categories = Categories::where(['deleted'=>0,'active'=>1])->get();
            $divisions = Divisions::where(['deleted' => 0,'active'=>1])->get();
            $prds = \App\Models\ProductModel::select('product.id', 'product_name')->join('product_category','product_category.product_id','product.id')->where(['product.deleted' => 0, 'product.product_status' => 1,'product_category.category_id'=>$banner->category_id])->get();
            return view('admin.banner.edit', compact('page_heading', 'banner','categories','divisions','prds'));
        } else {
            abort(404);
        }
    }
    // public function edit_web_banner($id = '')
    // {
    //     $banner = WebBannerModel::find($id);
    //     if ($banner) {
    //         $page_heading = "Edit Web Banner";
    //         return view('admin.banner.edit_web_banner', compact('page_heading', 'banner'));
    //     } else {
    //         abort(404);
    //     }
    // }

    public function update(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = '';
        $validator = Validator::make($request->all(),
            [
                // 'banner_title' => 'required',
                'banner' => 'image',
            ],
            [
                // 'banner_title.required' => 'Title required',
                'banner.image' => 'should be in image format (.jpg,.jpeg,.png)',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $ins['active'] = $request->active;
            $ins['banner_title'] = $request->banner_title;
            $ins['updated_at'] = gmdate('Y-m-d H:i:s');
            $ins['updated_by'] = session("user_id");
            $ins['category_id'] = $request->category_id ?? 0;
            $ins['division_id'] = $request->division_id ?? 0;
            $ins['product_id'] = $request->product_id ?? 0;
            if ($file = $request->file("banner")) {
                $dir = config('global.upload_path') . "/" . config('global.banner_image_upload_dir');
                $file_name = time() . uniqid() . "_banner." . $file->getClientOriginalExtension();
                $file->move($dir, $file_name);
                //$file->storeAs(config('global.banner_image_upload_dir'),$file_name,'s3');
                $ins['banner_image'] = $file_name;
            }
            if (BannerModel::where('id', $request->id)->update($ins)) {

                $status = "1";
                $message = "Banner updated";
                $errors = '';
            } else {
                $status = "0";
                $message = "Something went wrong";
                $errors = '';
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
    }
    // public function update_web_banner(Request $request)
    // {
    //     $status = "0";
    //     $message = "";
    //     $errors = '';
    //     $validator = Validator::make($request->all(),
    //         [
    //             // 'banner_title' => 'required',
    //             'banner' => 'image',
    //         ],
    //         [
    //             // 'banner_title.required' => 'Title required',
    //             'banner.image' => 'should be in image format (.jpg,.jpeg,.png)',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         $status = "0";
    //         $message = "Validation error occured";
    //         $errors = $validator->messages();
    //     } else {
    //         $ins['active'] = $request->active;
    //         $ins['banner_title_1'] = $request->banner_title_1;
    //         $ins['banner_title_2'] = $request->banner_title_2;
    //         $ins['banner_title_3'] = $request->banner_title_3;
    //         $ins['banner_title_4'] = $request->banner_title_4;
    //         $ins['updated_on'] = gmdate('Y-m-d H:i:s');
    //         $ins['updated_by'] = session("user_id");
    //         if ($file = $request->file("banner")) {
    //             if(isset($request->cropped_upload_image) && $request->cropped_upload_image){
    //                 $image_parts = explode(";base64,", $request->cropped_upload_image);
    //                 $image_type_aux = explode("image/", $image_parts[0]);
    //                 $image_type = $image_type_aux[1];
    //                 $image_base64 = base64_decode($image_parts[1]);
    //                 $imageName = uniqid() .time(). '.'.$image_type;
    //                 $path = \Storage::disk('s3')->put(config('global.banner_image_upload_dir').$imageName, $image_base64);
    //                 $path = \Storage::disk('s3')->url($path);
    //                 $ins['banner_image'] = $imageName;
    //             }else{
    //                 $dir = config('global.upload_path') . "/" . config('global.banner_image_upload_dir');
    //                 $file_name = time() . $file->getClientOriginalName();
    //                 //$file->move($dir, $file_name);
    //                 $file->storeAs(config('global.banner_image_upload_dir'),$file_name,'s3');
    //                 $ins['banner_image'] = $file_name;
    //             }
    //         }
    //         if (WebBannerModel::where('id',$request->id)->update($ins)) {

    //             $status = "1";
    //             $message = "Banner updated";
    //             $errors = '';
    //         } else {
    //             $status = "0";
    //             $message = "Something went wrong";
    //             $errors = '';
    //         }
    //     }
    //     echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);die();
    // }
    public function delete($id = '')
    {
        BannerModel::where('id', $id)->delete();
        $status = "1";
        $message = "Banner removed successfully";
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    // public function delete_web_banner($id = '')
    // {
    //     WebBannerModel::where('id', $id)->delete();
    //     $status = "1";
    //     $message = "Banner removed successfully";
    //     echo json_encode(['status' => $status, 'message' => $message]);
    // }

}
