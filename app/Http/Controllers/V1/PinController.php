<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\ResponseService;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\NewPinRequest;
use App\Http\Requests\ValidatePinRequest;
use App\Services\PinService;
use Illuminate\Http\Request;

class PinController extends Controller
{
    public $pinService,$customer,$key='#22!3!@3#',$hash='@*2734)';

    /**
     * PinController constructor.
     * @param PinService $pinService
     */
    public function __construct(PinService $pinService)
    {
        $this->pinService = $pinService;
    }

    /**
     * @param NewPinRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(NewPinRequest $request){
        $validated = $request->all();
         return $this->pinService->create(auth()->user(),$validated);
    }

    /**
     * @param NewPinRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(NewPinRequest $request)
    {
        $validated = $request->all();
         return $this->pinService->update(auth()->user(),$validated);
    }

    /**
     * @param ValidatePinRequest $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function validatePin(ValidatePinRequest $request)
    {
       $validated = $request->all();

        return $this->pinService->validate($validated['pin'],auth()->user()->transaction_pin);
    }
}
