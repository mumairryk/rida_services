<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Videos;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Videos";
        $vendor = \request()->get('vendor');

        $list = Videos::select('videos.*', 'users.name as vendor')->where('videos.deleted', 0)
            ->leftjoin('users', 'users.id', 'videos.vendor_id')
            ->where('videos.vendor_id', $vendor);

        $list = $list->orderBy('videos.id', 'DESC')->paginate(10);
        return view('admin.vendor.videos', compact('page_heading', 'list', 'vendor'));
    }

    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $video = Videos::find($id);
        if ($video) {
            $video->deleted = 1;
            $video->active = 0;
            $video->updated_at = gmdate('Y-m-d H:i:s');
            $video->save();
            $status = "1";
            $message = "Video removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Videos::where('id', $request->id)->update(['active' => $request->status])) {
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
