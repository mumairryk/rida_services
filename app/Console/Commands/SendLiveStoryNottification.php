<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\Stories;
use App\Models\User;
use App\Models\UserFollow;
class SendLiveStoryNottification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_nottification:live_story {story_id}';

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



          //post to firebase nottification table for follower
          $nottification_id = time();
          $nottifcation_items = [];
          $fcm_tokens_taged = $fcm_tokens = [];
          $nodeData = [
            'type'     => 'live_story',
            'post_id'  => $post_id,
            'title'  => 'Live now',
            // 'description'    => $post->user->name." is live now ",
            'description'    =>"is live now ",
            'channel_id' => $post->channel_id,
            'channel_key' => $post->channel_key,
            'width' => $post->width,
            'height' => $post->height,
            'zoom' => $post->zoom,
            'createdDate'     => $post->created_at,
            'imageUrl' => $post->path,
            'time'    => $nottification_id,
            'read'      => "0",
            'seen'      => "0",
            'user_key'  => $post->user->firebase_user_key
          ];
          $title = "New Story";
          $description = $post->user->name." is live now ";
          foreach($follower_id_value as $key){
            if($key['firebase_user_key'] != ''){
               $nottifcation_items[$key['firebase_user_key'].'/'.$nottification_id] = $nodeData;
             }
             if($key['user_device_token'] != ''){
               $fcm_tokens[] = $key['user_device_token'];
             }
          }


          //printr($nottifcation_items);
          if(!empty($nottifcation_items)){
            $this->database->getReference('Nottifications/')->update($nottifcation_items);
          }
          $ntype          = 'live-notification';
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
                             "post_id"=>(string)$post_id,
                             'title'  => 'Live now',
                             'description'    => $post->user->name." is live now ",
                             'channel_id' => $post->channel_id,
                             'channel_key' => $post->channel_key,
                             'width' => $post->width,
                             'height' => $post->height,
                             'zoom' => $post->zoom,
                         ]);
          }

        }
        return 0;
    }
}
