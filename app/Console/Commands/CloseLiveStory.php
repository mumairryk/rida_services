<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stories;
use Carbon\Carbon;

class CloseLiveStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:stop';

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

        $result = Stories::where('created_at', '<=', Carbon::now()->subMinute(30)->toDateTimeString())->where(['is_live'=>1])->update(['is_live'=>2,'status'=>0]);
        if($result > 0){
            echo "closed $result items";
        }else{
            echo "nothing to close";
        }
        return 0;
    }
}
