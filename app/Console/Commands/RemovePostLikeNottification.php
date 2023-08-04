<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\Post;
use App\Models\PostLikes;
use App\Models\NottificationTrack;

class RemovePostLikeNottification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove_nottification:post_like {post_id} {liked_user_id}';

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
        $liked_user_id = $this->argument('liked_user_id');

        $track = NottificationTrack::with(['user'])->where(['type'=>'post_like','from_user_id'=>$liked_user_id,'post_id'=>$post_id])->get();
        if($track->count() > 0){
            $track=$track->first();
            $post =Post::with(['user','post_users.user'])->where(['id'=>$track->key_id])->get();
            if($post->count() > 0){
                $data = $post->first();
                $fb_user_refrence = $this->database->getReference('Nottifications/'.$data->user->firebase_user_key.'/'.$data->firebase_node_id)->remove();

                foreach($data->post_users as $key){
                    $fb_user_refrence = $this->database->getReference('Nottifications/'.$key->user['firebase_user_key'].'/'.$data->firebase_node_id)->remove();
                }
                NottificationTrack::where(['id'=>$data->id])->delete();
            }

        }
        return 0;
    }
}
