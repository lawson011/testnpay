<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use App\Services\Auth\ResetPasswordService;

class ResetPasswordController extends Controller
{
    protected $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function requestResetForgotPassword(ForgotPasswordRequest $request)
    {
        return $this->resetPasswordService->requestResetForgotPassword($request);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->resetPasswordService->resetPassword($request);
    }

}

