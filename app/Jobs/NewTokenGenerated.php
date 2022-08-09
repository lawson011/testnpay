<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\TokenGenerated;
use Illuminate\Support\Facades\Mail;

class NewTokenGenerated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viewData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($viewData)
    {
        $this->viewData =  $viewData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->viewData['email'])->send(new TokenGenerated($this->viewData['token']));

        sendSms([
            'AccountNumber' => $this->viewData['AccountNumber'],
            'To' => $this->viewData['To'],
            "Body" => $this->viewData['Body'],
            "ReferenceNo" => $this->viewData['ReferenceNo']
        ]);
    }
}
