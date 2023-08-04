<?php
function headers() {
  // return array(
  //     "Authorization: key= AAAAFdq5Nws:APA91bFzHb_fY870jbVIunXfPruSkAUOGIrHzqDO8DLBr_fmtgiPTQqhstpiPuPVHNQXMNVAvnr3It3wp4oyTdLW0o03XedL2zLdUCGprHQao6c-HaoGxngVZ6a1gkXQKXj0YJEHWzbv",
  //     "Content-Type: application/json",
  //     "project_id: hop-web-3f7ab"

  // );
  return array(
        "Authorization: key=".config('firebase.FIREBASE_AUTH_KEY'),
        "Content-Type: application/json",
        "project_id: ".config('firebase.FIREBASE_PROJECT_ID')

    );
}

function prepare_notification($database,$user, $title, $description, $ntype = 'service',$record_id='',$record_type = '',$image_url = '') {
    
   
    $notification_id = time();

    if (!empty($user->firebase_user_key)) {
        $notification_data["Nottifications/" . $user->firebase_user_key . "/" . $notification_id] = [
            "title" => $title,
            "description" => $description,
            "notificationType" => $ntype,
            'createdDate' => gmdate("d-m-Y H:i:s", $notification_id),
            "serviceId" => (string) $record_id,
            "record_type" => $record_type,
            "url" => "",
            "imageURL" => $image_url,
            "read" => "0",
            "seen" => "0",
        ];
        $database->getReference()->update($notification_data);
    }

    if (!empty($user->user_device_token)) {
        send_single_notification($user->user_device_token, [
            "title" => $title,
            "body" => $description,
            "icon" => 'myicon',
            "sound" => 'default',
            "click_action" => "EcomNotification"],
            ["type" => $ntype,
                "notificationID" => $notification_id,
                "serviceId" => (string) $record_id,
                "imageURL" => $image_url,
            ]);
    }

}

function send_single_notification($fcm_token, $notification, $data, $priority = 'high') {
    $fields = array(
        'notification' => $notification,
        'data'=>$data,
        'content_available' => true,
        'priority' =>  $priority,
        'to' => $fcm_token
    );

    if ( $curl_response =  send(json_encode($fields), "https://fcm.googleapis.com/fcm/send") ) {
        return json_decode($curl_response);
    }
    else
        return false;
}

 function send_multicast_notification($fcm_tokens, $notification, $data, $priority = 'high') {
    $fields = array(
        'notification' => $notification,
        'data'=>$data,
        'content_available' => true,
        'priority' =>  $priority,
        'registration_ids' => $fcm_tokens
    );

    if ( $curl_response=send(json_encode($fields), "https://fcm.googleapis.com/fcm/send") ) {
        return json_decode($curl_response);
    }
    else
        return false;
}

 function send_notification($notification_key, $notification, $data, $priority = 'high') {
    $fields = array(
        'notification' => $notification,
        'data'=>$data,
        'content_available' => true,
        'priority' =>  $priority ,
        'to' => $notification_key
    );

    if ( $curl_response=send(json_encode($fields), "https://fcm.googleapis.com/fcm/send") ) {
        return json_decode($curl_response);
   }
   else
        return false;

}

 function send($fields,  $url ="", $headers = array() ) {

    if(empty($url)) $url = FIREBASE_URL;

    $headers = array_merge(headers(), $headers);

    $ch = curl_init();

    if (!$ch)  {
        $curl_error = "Couldn't initialize a cURL handle";
        return false;
    }

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $curl_response = curl_exec($ch);

    if(curl_errno($ch))
        $curl_error = curl_error($ch);

    if ($curl_response == FALSE) {
        return false;
    }
    else {
        $curl_info = curl_getinfo($ch);
        //printr($curl_info);
        curl_close($ch);
        return $curl_response;
    }

}

if (!function_exists('getUserId')) {
    function getUserId($access_token)
    {
        $user_id = 0;
        $user = \App\Models\User::where(['user_access_token' => $access_token])->where('user_access_token', '!=', '')->get();
        if ($user->count() > 0) {
            $user_id = $user->first()->id;
        }

        return $user_id;
    }
}
?>
