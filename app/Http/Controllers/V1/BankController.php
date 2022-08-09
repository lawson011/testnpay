<?php

namespace App\Http\Controllers\V1;

use App\Services\BankOne\ThirdPartyApiService\BankService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }
  
    
    public function index()
    {
        return $this->bankService->bankOneAllBanks();
    }
    

    public function find(Request $request)
    { 
        $validated = $request->validate([
            'cbn_code' => 'required'
        ]);

        return $this->bankService->get($validated['cbn_code']);
    }
}
