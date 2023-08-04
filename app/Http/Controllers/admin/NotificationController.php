<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\User;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;

class NotificationController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function notifications(Request $request)
    {
        if (!check_permission('notification', 'View')) {
            abort(404);
        }
        $page_heading = 'Notifications List';
        $notification_list = Notifications::orderBy('id', 'desc')->get();
        return view('admin.notifications.index', compact('page_heading', 'notification_list'));
    }

    public function create(Request $request)
    {
        if (!check_permission('notification', 'Create')) {
            abort(404);
        }
        $page_title = "Add New Notifications";
        $sellers = VendorModel::select('users.id', 'name')->where('role', '3')
            ->get();
        return view('admin.notifications.form', compact('page_title', 'sellers'));
    }

    public function save(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $notifications = new Notifications();

            $notifications->title = $request->title;
            $notifications->description = $request->description;
            $notifications->created_at = gmdate('Y-m-d H:i:00');
            $file_name = "";
            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.notification_image_upload_dir'), $file_name, config('global.upload_bucket'));
            }
            $notifications->image = $file_name;
            if ($notifications->save()) {
                $providersList = [];
                $image = $notifications->image;

                if (!empty($file_name)) {
                    $file_name = $image;
                }

                $usersListQry = User::where(['active' => 1, 'role' => 2, 'users.deleted' => '0'])->whereNotNull('firebase_user_key')->get();
                if (!empty($usersListQry)) {
                    foreach ($usersListQry as $user) {
                        if (!empty($user->user_device_token)) {
                            $providersList[] = [
                                'user_id' => $user->id,
                                'user_device_token' => $user->user_device_token,
                                'firebase_user_key' => $user->firebase_user_key,
                            ];
                        }
                    }
                }

                if (!empty($providersList)) {
                    $bd_data = [];
                    $push_dat = [];
                    $title = $request->title;
                    $description = $request->description;
                    $ntype = 'public-notification';
                    $notification_id = time();
                    //print_r($providersList);

                    foreach ($providersList as $seller) {

                        $in_data = [
                            "title" => $title,
                            "description" => $description,
                            "notificationType" => $ntype,
                            "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                            "url" => "",
                            "imageURL" => (string) $file_name,
                            "read" => "0",
                            "seen" => "0",
                        ];
                        if ($seller['user_device_token'] != "") {
                            $push_dat[] = $seller['user_device_token'];
                        }
                        if ($seller['firebase_user_key'] != "") {
                            $bd_data["Nottifications/" . $seller['firebase_user_key'] . "/" . $notification_id] = $in_data;
                        }

                    }

                    if (!empty($push_dat)) {

                        $res = send_multicast_notification($push_dat,
                            [
                                "title" => $title,
                                "body" => $description,
                                "icon" => 'myicon',
                                "sound" => 'default',
                                "click_action" => "EcomNotification",
                            ],
                            [
                                "type" => $ntype,
                                "notificationID" => $notification_id,
                                "imageURL" => (string) $file_name,
                            ]);
                        //echo "<pre>sss";
                        //print_r($push_dat); exit;
                    }
                    if (!empty($bd_data)) {
                        $this->database->getReference()->update($bd_data);
                    }
                }

                $status = "1";
                $message = "Notification sent successfully";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
        // return redirect()->route('admin.notifications')->with('message', $message);
    }

    public function delete(Request $request)
    {
        $status = "0";
        $message = "Something went wrong.";
        $record = Notifications::find($request->id);
        if ($record) {
            $record->delete();
            $status = "1";
            $message = "Record has been delete successfully";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }

}
