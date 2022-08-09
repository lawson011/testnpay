<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DetachedDevice extends BaseMailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $username,$devicename;

    public function __construct($username,$devicename)
    {
        parent::__construct();
        $this->username = $username;
        $this->devicename = $devicename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Detached Successfully')->markdown('emails.device.detached');
    }
}
