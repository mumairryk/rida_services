<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\HidePost;

class PostHide extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:hide_post {hide_id} {option}';

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
      $hide_id = $this->argument('hide_id');
      $option = $this->argument('option');

      $data = HidePost::with('post','user')->where(['id'=>$hide_id])->get();
      if($data->count() > 0){
         $data= $data->first();
         if($option =='hide'){
           $fb_user_refrence = $this->database->getReference('SocialUserPosts/'.$data->user->firebase_user_key.'/'.$data->post->post_firebase_node_id.'/')->update(['visibility'=>'hide']);
         }else{
            $fb_user_refrence = $this->database->getReference('SocialUserPosts/'.$data->user->firebase_user_key.'/'.$data->post->post_firebase_node_id.'/')->update(['visibility'=>'show']);

          }
        }else{

        }
        return 0;
    }
}
