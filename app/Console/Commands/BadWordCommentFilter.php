<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\BadWordFinder;
use App\Models\PostComment;
use Kreait\Firebase\Contract\Database;
use App\Models\HashTags;

class BadWordCommentFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bad_word:comment {comment_id}';

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
        $post_id = $this->argument('comment_id');
        $post = PostComment::with('commented_user')->find($post_id);
        if($post->id){
            $badWordFinder = new BadWordFinder();
            $status = $badWordFinder->check_word($post->comment);
            if($status == 1){
                $post->is_bad_word_exist = $badWordFinder->is_bad_word_exist;
                $cmnt = $post->comment;
                foreach($badWordFinder->bad_words as $key){
                    $cmnt = str_ireplace($key,'***',$cmnt);
                }
                $post->comment = $cmnt;
                $post->bad_words = implode(",",$badWordFinder->bad_words);
                $post->active=0;
                $post->save();

                if($badWordFinder->is_bad_word_exist == 1){
                    $nottification_id = time();
                    if($post->commented_user->firebase_user_key != ''){

                        $nodeData = [
                          'type'     => 'bad_word',
                          'post_id'  => $post->id,
                          'title'    => "You comment cantain bad words ",
                          'description'=>"Hi your comment cantain bad words",
                          'createdDate'     => $post->created_at,
                          'imageUrl' => '',
                          'time'    => $nottification_id
                        ];
                        $this->database->getReference('Nottifications/'.$post->commented_user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
                    }
                    if($post->commented_user->user_device_token != ''){
                        $ntype          = 'bad_word_comment';
                        $title = "Bad word detected";
                        $description = "Your latest comment cantains some bad words";
                        $res = send_multicast_notification([$post->commented_user->user_device_token],
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
                                         "post_id"=>$post->id
                                     ]);
                    }
                }
            }
        }
        $post = PostComment::find($post_id);
        $hash_tags = retrive_hash_tags($post->comment);
        foreach($hash_tags as $tag){
            $new_tag = HashTags::firstOrNew(['tag'=>$tag]);
            $new_tag->tag = $tag;
            $new_tag->created_at = gmdate('Y-m-d H:i:s');
            $new_tag->updated_at = gmdate('Y-m-d H:i:s');
            $new_tag->save();
        }
        return 0;
    }
}
