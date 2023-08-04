<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;
use App\Models\PostUsers;
use App\Models\PostLikes;
use App\Models\NottificationTrack;
use Kreait\Firebase\Contract\Database;

class SendNottificationPostLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_nottification:post_like {like_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $like_id = $this->argument('like_id');
      $like = PostLikes::with('liked_user','post','post.user','post.post_users.user')->where(['id'=>$like_id])->get();
      if($like->count() > 0){
        $like = $like->first();
        if(isset($like->post->user->id)){
          if($like->liked_user->id==$like->post->user->id){
            return 0;
          }
        }

        $nottification_id = time();
        $nottifcation_items = [];
        $fcm_tokens_taged = $device_tokens = [];
        $nodeData = [
          'type'     => 'post_like',
          'post_id'  => (string)$like->post->id,
          'title'=> 'Post Liked',
          // 'description'    => $like->liked_user->name." liked your post ",
          'description'    => "liked your post ",
          'post_firebase_key' => $like->post_firebase_node_id,
          'createdDate'     => $like->created_at,
          'imageUrl' => $like->post->file,
          'file_type' => $like->post->file_type,
          'time'    => $nottification_id,
          'read'      => "0",
          'seen'      => "0",
          'liked_user_node_id'=>$like->liked_user->firebase_user_key
        ];
        $title = "Post Like";
        $description = $like->liked_user->name." liked your post ";
        if($like->post->user->firebase_user_key != ''){
          $this->database->getReference('Nottifications/'.$like->post->user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
        }
        $track = new NottificationTrack();
        $track->type= 'post_like';
        $track->from_user_id = $like->liked_user->id;
        $track->to_user_id   = 0;
        $track->key_id = $like->post->id;
        $track->firebase_node_id = $nottification_id;
        $track->save();
        $ntype          = 'post-like-notification';
        if($like->post->user->user_device_token != ''){
          $res = send_single_notification($like->post->user->user_device_token,
                       [
                           "title" => $title,
                           "body" => $description,
                           "icon" => 'myicon',
                           "sound" => 'default',
                           "click_action" => "EcomNotification",
                       ],
                       [
                           "type" => $ntype,
                           "notificationID" => $nottification_id,
                           "key_id" => $like->post->id,
                           "imageURL" => (string)$like->post->file,
                       ]);
        }
        //printr($like->post->toArray()); exit;
        foreach($like->post->post_users as $key){
          //printr($key->user['firebase_user_key']);
          if($key->user['firebase_user_key'] != ''){
            $fcm_tokens_taged[$key->user['firebase_user_key'].'/'.$nottification_id] = $nodeData;
          }
          if($key->user['user_device_token'] != ''){
            $device_tokens[] = $key->user['user_device_token'];
          }
        }
        //printr($fcm_tokens_taged);
        if(!empty($fcm_tokens_taged)){
          $this->database->getReference('Nottifications/')->update($fcm_tokens_taged);
        }
        if(!empty($device_tokens)){
          $res = send_multicast_notification($device_tokens,
          [
              "title" => $title,
              "body" => $description,
              "icon" => 'myicon',
              "sound" => 'default',
              "click_action" => "EcomNotification",
          ],
          [
              "type" => $ntype,
              "notificationID" => $nottification_id,
              "key_id" => $like->post->id,
              "imageURL" => (string)$like->post->file,
          ]);
        }


      }
      return 0;
    }
}
