<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PostComment;
use App\Models\CommentTagedUsers;
use App\Models\CommentLikes;
use Kreait\Firebase\Contract\Database;
class SendNottificationCommentLike extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_nottification:comment_like {like_id}';

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
       $like = CommentLikes::with('liked_user','comment.commented_user','comment.post','comment.post.user','comment.post.post_users.user')->where(['id'=>$like_id])->get();

       if($like->count() > 0){
         $like = $like->first();
         $nottification_id = time();
         $nottifcation_items = [];
         $fcm_tokens_taged = $device_tokens = [];
         $nodeData = [
           'type'     => 'comment_like',
           'post_id'  => (string)$like->comment->post->id,
           'comment_id'=> (string)$like->comment->id,
           'title'      => "Comment like",
          //  'description'    => $like->liked_user->name." liked a comment in your post ",
           'description'    => "liked a comment in your post ",
           'createdDate'     => $like->created_at,
           'imageUrl' => $like->comment->post->file,
           'file_type' => $like->comment->post->file_type,
           'time'    => $nottification_id,
           'read'      => "0",
           'seen'      => "0",
           'posted_user_firebase_key'=>$like->liked_user->firebase_user_key,
         ];
         $title = "Post Like";
         $description = $like->liked_user->name." liked a comment in your post ";
         if($like->comment->post->user->firebase_user_key != ''){
             if($like->comment->post->user->id != $like->liked_user->id ){
                $this->database->getReference('Nottifications/'.$like->comment->post->user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
            }
         }
         if($like->liked_user->id != $like->comment->commented_user->id){
             $nodeData['description'] = $like->liked_user->name." liked a comment ";
             if($like->liked_user->firebase_user_key != ''){
                 $this->database->getReference('Nottifications/'.$like->liked_user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
             }
         }
         $ntype          = 'comment-like-notification';


         if($like->comment->post->user->user_device_token != ''){
             if($like->comment->post->user->id != $like->liked_user->id ){
               $res = send_single_notification($like->comment->post->user->user_device_token,
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
                                "key_id" => $like->comment->id,
                                "imageURL" => (string)'',
                                'post_id'  => (string)$like->comment->post->id,
                            ]);
                            printr($res);
                        }
         }
         if($like->liked_user->id != $like->comment->commented_user->id){
             if($like->comment->commented_user->user_device_token != ''){
                 $res = send_single_notification($like->comment->commented_user->user_device_token,
                            [
                                "title" => $title,
                                "body" => $like->liked_user->name." liked a comment ",
                                "icon" => 'myicon',
                                "sound" => 'default',
                                "click_action" => "EcomNotification",
                            ],
                            [
                                "type" => $ntype,
                                "notificationID" => $nottification_id,
                                "key_id" => $like->comment->id,
                                "imageURL" => (string)'',
                                'post_id'  => (string)$like->comment->post->id,
                            ]);
                            printr($res);
             }
         }

        //  $nodeData = [
        //   'type'     => 'comment_like',
        //   'post_id'  => (string)$like->comment->post->id,
        //   'comment_id'=> (string)$like->comment->id,
        //   'title'    => " Comment like",
        //   //  'description'=>$like->liked_user->name." liked your comment ",
        //   'description'=>"liked your comment ",
        //   'createdDate'     => $like->created_at,
        //   'imageUrl' => $like->comment->post->file,
        //   'file_type' => $like->comment->post->file_type,
        //   'time'    => $nottification_id
        //  ];
        //  $title = "Post Like";
        //  $description = $like->liked_user->name." liked your comment ";

        //  if($like->comment->commented_user->firebase_user_key != ''){
        //   $this->database->getReference('Nottifications/'.$like->comment->commented_user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
        //  }
        //  $ntype          = 'comment-like-notification';




       }
       return 0;
     }
}
