<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Photos;
use Illuminate\Http\Request;
use Auth;

class PicturesController extends Controller
{
    public function index()
    {
        $vendor_id = Auth::user()->id;
        $page_heading = "Pictures";

        $list = Photos::select('photos.*', 'users.name as vendor')->where('photos.deleted', 0)
            ->leftjoin('users', 'users.id', 'photos.vendor_id')
            ->where('photos.vendor_id', $vendor_id);

        $list = $list->orderBy('photos.id', 'DESC')->paginate(10);
        return view('vendor.gallery.pictures', compact('page_heading', 'list'));
    }

    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $picture = Photos::find($id);
        if ($picture) {
            $picture->deleted = 1;
            $picture->active = 0;
            $picture->updated_at = gmdate('Y-m-d H:i:s');
            $picture->save();
            $status = "1";
            $message = "Picture removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Photos::where('id', $request->id)->update(['active' => $request->status])) {
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

}
