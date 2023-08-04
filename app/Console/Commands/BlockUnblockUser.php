<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\User;

class BlockUnblockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'firebase:block_unblock_user {loged_user_id} {user_id} {option}';

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

      

      $loged_user_id = $this->argument('loged_user_id');
     
      $user_id = $this->argument('user_id');
      $option = $this->argument('option');

      $logged = User::select('firebase_user_key')->where('id',$loged_user_id)->first();
      $user = User::select('firebase_user_key')->where('id',$user_id)->first();
      if($logged->firebase_user_key && $user->firebase_user_key){
         if($option =='unblock'){
           $fb_user_refrence = $this->database->getReference('BlockedUser/'.$logged->firebase_user_key.'/'.$user->firebase_user_key.'/')->remove();
         }else{
            $fb_user_refrence = $this->database->getReference('BlockedUser/'.$logged->firebase_user_key.'/'.$user->firebase_user_key.'/')->update(['blocked_at'=>gmdate('Y-m-d H:i:s')]);
          }
        }else{

        }
        return 0;
    }
}
