<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BaseMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->callbacks[]=(function($message){
            $message->getHeaders()->addTextHeader('X-SES-MESSAGE-TAGS', 'npay_emails=notifynuturemfbank');
        });
    }

}
