<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\User;
use App\Models\UserFollow;
use App\Models\NottificationTrack;

class RemoveNottification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove_nottification:follow {user_id} {follow_user_id}';

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
        $user_id = $this->argument('user_id');
        $follow_user_id = $this->argument('follow_user_id');

        $check = NottificationTrack::with(['followed_user','user'])->where(['type'=>'user_follow','from_user_id'=>$user_id,'to_user_id'=>$follow_user_id])->get();
        if($check->count() > 0){
            $data = $check->first();
            $fb_user_refrence = $this->database->getReference('Nottifications/'.$data->user->firebase_user_key.'/'.$data->firebase_node_id)->remove();
            $fb_user_refrence = $this->database->getReference('Nottifications/'.$data->followed_user->firebase_user_key.'/'.$data->firebase_node_id)->remove();
            NottificationTrack::where(['id'=>$data->id])->delete();
        }
        return 0;
    }
}
