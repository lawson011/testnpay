<?php

namespace App\Exceptions;

use Exception;

class AccountEnquiryException extends Exception
{

    public function report()
    {
        \Log::error('Account Information Retrieval Failed');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'status'  => false,
            'messsage' => 'Account Information Retrieval failed'
        ],412);
    }
}
