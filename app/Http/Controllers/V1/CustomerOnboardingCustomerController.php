<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOnboardingCustomerRequest;
use App\Services\CustomerOnboardingCustomerService;

class CustomerOnboardingCustomerController extends Controller
{
    protected $customerOnboardingCustomerService;

    public function __construct(CustomerOnboardingCustomerService $customerOnboardingCustomerService)
    {
        $this->customerOnboardingCustomerService = $customerOnboardingCustomerService;
    }

    public function store(StoreOnboardingCustomerRequest $request){

        return $this->customerOnboardingCustomerService->store($request);
    }
}
