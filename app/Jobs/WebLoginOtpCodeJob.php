<?php

namespace App\Jobs;

use App\Mail\webLoginOtpCodeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class webLoginOtpCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $token,$email,$phone,$sms;
    public function __construct($token, $email, $phone, $sms)
    {
        $this->token = $token;
        $this->email = $email;
        $this->phone = $phone;
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //send sms here
        if ($this->phone)
        sendSms($this->sms);

        //send email
        if ($this->email)
            Mail::to($this->email)->send(new webLoginOtpCodeMail($this->token));
    }
}
