<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Customers\Models\CustomerGuarantor;
use App\Http\Requests\CustomerGuarantorRequest;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerGuarantorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerGuarantorRequest $request
     *
     * @return JsonResponse
     */
    public function store(CustomerGuarantorRequest $request): JsonResponse
    {
        CustomerGuarantor::create([
            'customer_id' => Auth::id(),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address')
        ]);

        return (new ResponseService())->getSuccessResource();
    }

}
