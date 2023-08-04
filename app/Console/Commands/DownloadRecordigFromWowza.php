<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stories;

class DownloadRecordigFromWowza extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wowza:download {post_id}';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $post_id = $this->argument('post_id');
        $data  = Stories::find($post_id);

        $url = "http://moda.uksouth.cloudapp.azure.com/mrecordings/".$data->channel_id.".mp4";
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        if( \Storage::disk(config('global.upload_bucket'))->put(config('global.post_image_upload_dir').$name, $contents) ){
            $data->live_url = $name;
            $data->save();
        }else{
            echo "faild to download";
        }

        return 0;
    }
}
