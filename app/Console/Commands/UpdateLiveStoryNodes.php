<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\Stories;
use App\Models\User;
use App\Models\UserFollow;

class UpdateLiveStoryNodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:save_live_story {post_id}';

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
       $post_id = $this->argument('post_id');
       $data  = Stories::with('post_users','post_users.user','user')->find($post_id);

       $o_data = [
           'channelID'  => (string)$data->channel_id,
           'user_firbase_key' => (string)$data->user->firebase_user_key,
           'createdAt'  => (string)$data->created_at,
           'thumbImageUrl'=> $data->path,
           'width'  => (string)$data->width,
           'height'  => (string)$data->height,
           'zoom'  => (string)$data->zoom,
        ];
       if($data->channel_key ==''){
         //friebase insert
         $fb_user_refrence = $this->database->getReference('SocialLiveStream/')
             ->push($o_data);

         $post = Stories::find($post_id);
         $post->channel_key = $post_node= $fb_user_refrence->getKey();
         $post->save();
         $message = "post saved";
         $post_node = $post->channel_key;

       }else{
         $fb_user_refrence = $this->database->getReference('SocialLiveStream/')
             ->update([$data->channel_key => $o_data]);

             $message = "Post updated Successfully";
             $post_node = $data->channel_key;
       }

       $followers = UserFollow::with('follower')->where(['follow_user_id'=>$data->user_id])->get();
       $user_nodes = [];
       foreach($followers as $follow){
         if($follow->follower->firebase_user_key){
           $user_nodes[$follow->follower->firebase_user_key.'/'.$post_node] = [
             'created_at'  => $data->created_at,
             'channelID'   => $data->channel_id,
             'thumbImageUrl'=> $data->path,
             'user_firbase_key' => (string)$data->user->firebase_user_key
           ];
         }
       }
       if($data->user){
         $user_nodes[$data->user->firebase_user_key.'/'.$post_node] = [
             'created_at'  => $data->created_at,
             'channelID'   => $data->channel_id,
             'thumbImageUrl'=> $data->path,
             'user_firbase_key' => (string)$data->user->firebase_user_key
         ];
       }
       //printr($user_nodes);
       if(!empty($user_nodes)){
         $fb_user_refrence = $this->database->getReference('SocialFriendsLiveStream/')
             ->update($user_nodes);
       }
       echo $message;

         return 0;
     }
}
