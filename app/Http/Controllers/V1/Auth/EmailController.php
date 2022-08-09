<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Requests\ResendEmailValidationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyEmailRequest;
use App\Repositories\VerifyEmail\VerifyEmailInterface;

class EmailController extends Controller
{
    //

    protected $verifyEmail;

    public function __construct(VerifyEmailInterface $verifyEmail){
        $this->verifyEmail = $verifyEmail;
    }

    public function index(ResendEmailValidationRequest $request){

        return $this->verifyEmail->sendMessage($request->email);
    }
    public function verifyEmail(VerifyEmailRequest $request){
        return $this->verifyEmail->verifyEmail($request);
    }
}
