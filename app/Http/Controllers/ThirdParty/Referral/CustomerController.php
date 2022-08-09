<?php

namespace App\Http\Controllers\ThirdParty\Referral;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdParty\Referral\GetCustomerByNubanRequest;
use App\Http\Resources\ThirdParty\ReferalResource;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerAuth, $responseService;
    public function __construct(CustomerAuthInterface $customerAuth, ResponseService $responseService)
    {
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
    }

    public function getAll(Request $request)
    {
        $data = $this->customerAuth->getAll()->whereIn('referral_code',$request->referral_code)->get();

        $data->referred_check = false;
        return $this->responseService->getSuccessResource([
            'data' =>  ReferalResource::collection($data)
        ]);
    }

    public function getByReferralCode($code){

         $data = $this->customerAuth->findByColumn(
          [
              ['referral_code','=',$code]
          ]
        )->firstOrFail();

         if (!$data){
             return $this->responseService->getErrorResource([
                 'message' => 'Invalid referral code',
             ]);
         }
        $data->referred_check = true;
        return $this->responseService->getSuccessResource([
            'data' => new ReferalResource($data),
        ]);
    }

    /**
     * Get customer information using nuban
     *
     * @param GetCustomerByNubanRequest $request
     *
     * @return JsonResponse
     */
    public function customerDetails(GetCustomerByNubanRequest $request): JsonResponse
    {
        $data = $this->customerAuth->getAll()->whereIn('nuban',$request->input('nuban'))->get();

        if (!$data){
            return $this->responseService->getErrorResource([
                'message' => 'Invalid nuban',
            ]);
        }

        return $this->responseService->getSuccessResource([
            'data' =>  ReferalResource::collection($data)
        ]);
    }
}
