<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApplicationProcessFailedException extends Exception
{


    public function report()
    {
        \Log::error($this->message);
    }

    public function render()
    {
        return response()->json([
            'status'  => false,
            'messsage' => $this->message
        ],$this->code);
    }
}
