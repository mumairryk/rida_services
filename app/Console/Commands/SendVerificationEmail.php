<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class SendVerificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_verification_email {--uri=} {--uri2=} {--uri3=} {--uri4=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Verification Email';

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
        $subject = urldecode($this->option("uri"));
        $to =  urldecode($this->option("uri2"));
        $otp = $this->option("uri3");
        $name = urldecode($this->option("uri4"));
        $mailbody = view('emai_templates.verify_mail', compact('otp', 'name'));
        send_email($to, $subject, $mailbody);
    }
}
