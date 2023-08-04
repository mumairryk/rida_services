<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\BadWordFinder;
use App\Models\Post;
use App\Models\HashTags;
use Kreait\Firebase\Contract\Database;
class BadWordFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bad_word:post {post_id}';

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
        $post = Post::with('user')->find($post_id);
        if($post->id){
            $badWordFinder = new BadWordFinder();
            $status = $badWordFinder->check_word($post->caption);
            if($status == 1){
                $post->is_bad_word_exist = $badWordFinder->is_bad_word_exist;
                $post->bad_words = implode(",",$badWordFinder->bad_words);
                $post->active=0;
                $post->save();

                if($badWordFinder->is_bad_word_exist == 1){
                    $nottification_id = time();
                    if($post->user->firebase_user_key != ''){

                        $nodeData = [
                          'type'     => 'bad_word',
                          'post_id'  => $post->id,
                          'title'    => "You post cantain bad words ",
                          'description'=>"Hi your post cantain bad words in caption",
                          'createdDate'     => $post->created_at,
                          'imageUrl' => '',
                          'time'    => $nottification_id,
                          'read'      => "0",
                          'seen'      => "0"
                        ];
                        $this->database->getReference('Nottifications/'.$post->user->firebase_user_key.'/'.$nottification_id.'/')->update($nodeData);
                    }
                    if($post->user->user_device_token != ''){
                        $ntype          = 'bad_word';
                        $title = "Bad word detected";
                        $description = "Your latest post cantains some bad words in caption";
                        $res = send_multicast_notification([$post->user->user_device_token],
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

        $post = Post::find($post_id);
        $hash_tags = retrive_hash_tags($post->caption);
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
