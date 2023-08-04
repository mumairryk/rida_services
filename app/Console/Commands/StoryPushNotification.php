<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\Stories;
use App\Models\User;
use App\Models\UserFollow;
class StoryPushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_nottification:story {story_id}';

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
        $post_id = $this->argument('story_id');
        $post =Stories::with('post_users','user')->where(['id'=>$post_id])->get();
        if($post->count() > 0){
          $post = $post->first();
          $followers_ids = [];
          $follower_id_value = [];
          $taged_ids     = [];

          $post_added_by = $post->user;



          foreach($post->post_users as $tagUser){
            $taged_ids[] = $tagUser->user_id;
          }

          $followers = UserFollow::with('follower')->where(['follow_user_id'=>$post_added_by->id])->get();
          if($followers->count() > 0){
            foreach($followers as $followUser){
              //printr($followUser);
              if($post->user->id != $followUser->follower->id){
                   $followers_ids[] = $followUser->follower->id;
                   $follower_id_value[$followUser->follower->id] = $followUser->follower->toArray();
               }
            }

          }

          foreach($taged_ids as $tagKey){
            if(in_array($tagKey,$followers_ids)){
              if(isset($follower_id_value[$tagKey])){
                unset($follower_id_value[$tagKey]);
              }
            }
          }

          //post to firebase nottification table for follower
          $nottification_id = time();
          $nottifcation_items = [];
          $fcm_tokens_taged = $fcm_tokens = [];
          $nodeData = [
            'type'     => 'story',
            'story_id'  => (string)$post_id,
            'title'  => 'New Story',
            // 'description'    => $post->user->name." added new story ",
            'description'    => "added new story ",
            'createdDate'     => $post->created_at,
            'imageUrl' => $post->path,
            'time'    => $nottification_id,
            'read'      => "0",
            'seen'      => "0",
            'posted_user_firebase_key'=>$post->user->firebase_user_key,
            'posted_user_id'=>(string)$post->user->id
          ];
          $title = "New Story";
          $description = $post->user->name." added new story ";
          foreach($follower_id_value as $key){
            if($key['firebase_user_key'] != ''){
               $nottifcation_items[$key['firebase_user_key'].'/'.$nottification_id] = $nodeData;
             }
             if($key['user_device_token'] != ''){
               $fcm_tokens[] = $key['user_device_token'];
             }
          }

          //get tag user details
          $tag_user_lists = User::whereIn('id',$taged_ids)->get();
          foreach($tag_user_lists as $key){
            if($key->firebase_user_key != ''){
              $nodeData['title'] = " tagged you in a story";
              // $nodeData['description'] = $post->user->name." tagged you in a story";
              $nodeData['description'] = "tagged you in a story";
              $nottifcation_items[$key->firebase_user_key.'/'.$nottification_id] = $nodeData;
            }
            if($key->user_device_token != ''){
              $fcm_tokens_taged[] = $key->user_device_token;
            }
          }
          //printr($nottifcation_items);
          if(!empty($nottifcation_items)){
            $this->database->getReference('Nottifications/')->update($nottifcation_items);
          }
          $ntype          = 'story-notification';
          if(!empty($fcm_tokens)){
            $res = send_multicast_notification($fcm_tokens,
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
                             "imageURL" => (string)$post->file,
                             'story_id'  => (string)$post_id,
                             'title'  => 'New Story',
                             'description'    => $post->user->name." added new story ",
                             'posted_user_id'=>(string)$post->user->id
                         ]);
          }
          if(!empty($fcm_tokens_taged)){
            $decription = $post->user->name." tagged you in a story";
            $res = send_multicast_notification($fcm_tokens_taged,
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
                             "imageURL" => (string)$post->file,
                             "post_id"=>(string)$post_id,
                             'title'  => 'New Story',
                             'description'    => $decription,
                             'posted_user_id'=>(string)$post->user->id
                         ]);
          }
        }
        return 0;
    }
}
