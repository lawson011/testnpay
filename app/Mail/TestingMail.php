<?php

namespace App\Mail;


class TestingMail extends BaseMailable
{

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.testing.index');
    }
}
