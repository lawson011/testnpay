<?php

namespace App\Jobs;

use App\Mail\Admin\ApproveLoan;
use App\Mail\Admin\DeclineLoan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ApproveLoanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 5, $email, $firstName;
    public function __construct($email, $firstName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new ApproveLoan($this->firstName));
    }
}
