<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
class UpdateUserFirebaseNode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:firebase_node {user_id}';

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
        $user = \App\Models\User::where(['id'=>$user_id])->get();
        if($user->count() > 0){
          $user = $user->first();
          if($user->firebase_user_key != ''){
                $this->database->getReference('Users/' . $user->firebase_user_key . '/')->update([
                    'fcm_token' => (string)$user->user_device_token,
                    'name' => (string)$user->name,
                    'email'     => (string)$user->email,
                    'user_id'   => (string)$user->id,
                    'user_image'=> (string)$user->user_image,
                    'dial_code' => (string)$user->dial_code,
                    'phone'     => (string)$user->phone,
                    'last_login'=> (string)time()
                ]);
            }else{
                $user = \App\Models\User::find($user_id);
                $fb_user_refrence = $this->database->getReference('Users/')
                ->push([
                    'fcm_token' => (string)$user->user_device_token,
                    'name' => (string)$user->name,
                    'email'     => (string)$user->email,
                    'user_id'   => (string)$user->id,
                    'user_image'=> (string)$user->user_image,
                    'dial_code' => (string)$user->dial_code,
                    'phone'     => (string)$user->phone,
                    'last_login'=> (string)time()
                ]);
                $user->firebase_user_key = $fb_user_refrence->getKey();
                $user->save();
            }
        }
        return 0;
    }
}
