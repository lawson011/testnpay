<?php

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TokenService;
use App\Http\Requests\ValidateTokenRequest;

class TokenController extends  Controller
{
    public $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function generateToken(){
        return $this->tokenService->generate(auth()->user());
    }

    public function validateToken(ValidateTokenRequest $request){
        $validated = $request->validated();
        return $this->tokenService->validate($validated['token'],auth()->user());
    }
}
