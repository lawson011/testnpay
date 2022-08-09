<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Services\LoanServices\LoanControllerService;

class LoanController extends Controller
{
    protected $loanControllerService;

    public function __construct(LoanControllerService $loanControllerService)
    {
        $this->loanControllerService = $loanControllerService;
    }

    public function loanSetting(){
        return $this->loanControllerService->loanSetting();
    }

    public function apply(LoanRequest $request){
        return $this->loanControllerService->apply($request->all());
    }

    public function loanHistory(){
        return $this->loanControllerService->history();
    }

}
