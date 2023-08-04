<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\Post;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\PostComment;

class SendNottificationComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_nottification:comment {comment_id}';

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
        $fcm_tokens_taged = [];
        $nottifcation_items= [];

        $comment_id = $this->argument('comment_id');
        $comment    = PostComment::with('post','commented_user','post.user','post.post_users.user')->where(['id'=>$comment_id])->get();
        if($comment->count() > 0){
          $comment = $comment->first();
          $nottification_id = time();
          $nodeData = [
            'type'     => 'post_comment',
            'post_id'  => (string)$comment->post->id,
            'comment_id'=> (string)$comment->id,
            'title'    => "new comment ",
            // 'description'=>$comment->commented_user->name." added a comment ",
            'description'=>"added a comment ",
            'comment_firebase_key' => $comment->comment_node_id,
            'createdDate'     => $comment->created_at,
            'imageUrl' => '',
            'file_type' => '',
            'time'    => (string)$nottification_id,
            'read'      => "0",
            'seen'      => "0",
            'posted_user_firebase_key'=>$comment->commented_user->firebase_user_key,
          ];
            //printr($comment->post->user);
          if(!empty($comment->post->user)){
                echo $comment->post->user->firebase_user_key;
            if($comment->post->user->firebase_user_key != '' && $comment->commented_user->id != $comment->post->user->id){
              $this->database->getReference('Nottifications/'.$comment->post->user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
            }

          }
          if($comment->commented_user->id != $comment->post->user->id){
              $fcm_tokens_taged[] = $comment->post->user->user_device_token;
          }
          foreach($comment->post->post_users as $key){
            if($key->user->firebase_user_key != '' && $comment->post->user_id != $key->user->id){
              $nottifcation_items[$key->user->firebase_user_key.'/'.$nottification_id] = $nodeData;
            }
            if($key->user->user_device_token != '' && $comment->post->user_id != $key->user->id){
              $fcm_tokens_taged[] = $key->user->user_device_token;
            }
          }

          if(!empty($nottifcation_items))
            $this->database->getReference('Nottifications/')->update($nottifcation_items);
          $ntype          = 'comment-notification';
          $title = "New Comment added to your post";
          $description = $comment->comment;
          if(!empty($fcm_tokens_taged)){
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
                             "imageURL" => '',
                             'comment_id'=> (string)$comment->id,
                             'post_id'  => (string)$comment->post->id,
                         ]);
             printr($res);
          }
        }
        return 0;
    }
}
