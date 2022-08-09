<?php

namespace App\Exceptions;

use Exception;

class AccountValidationException extends Exception
{
    public function report()
    {
        \Log::error('Account Validation Failed');
    }

    public function render()
    {
        return response()->json([
            'status'  => false,
            'messsage' => 'Account validation failed'
        ],412);
    }
}
