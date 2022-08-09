<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerCard\CustomerCardInterface;
use App\Services\Paystack\PaystackService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCardController extends Controller
{
    const amountToChargeCard = 50;
    protected $customerCard, $paystackService, $customerAuth, $responseService;

    public function __construct(CustomerCardInterface $customerCard, PaystackService $paystackService,
                                CustomerAuthInterface $customerAuth, ResponseService $responseService)
    {
        $this->customerCard = $customerCard;
        $this->paystackService = $paystackService;
        $this->customerAuth = $customerAuth;
        $this->responseService = $responseService;
    }

    public function add(Request $request){

        //check if payment reference has already been use
        $checkCardReference = $this->customerCard->findByColumn([
            'reference'=>$request->reference
        ])->first();

        //if card has already been use
        if ($checkCardReference){
            return $this->responseService->getErrorResource(['message'=>'Payment reference has already been used']);
        }

        //Confirm the transaction by using reference
        //yb5qxmq7w87ltyg transaction reference for test
        $transRef = $this->paystackService->verifyTransaction($request->reference);

        if ($transRef['status']==true && $transRef['data']['status']=='success'){

            $amount = (($transRef['data']['amount'])/100);
            //confirm the the amount is valid
            if ($amount < self::amountToChargeCard){
                return $this->responseService->getErrorResource([
                    "message" => 'Amount charged should be 50 naira, '.($transRef['data']['amount'])/100 .' was charged'
                ]);
            }

            $authorized = $transRef['data']['authorization'];

            //check if card already registered
            $checkCard = $this->customerCard->findByColumn([
                'customer_id'=>$this->customerAuth->authCustomer()->id, 'signature'=>$authorized['signature'],
                'exp_month'=>$authorized['exp_month'], 'exp_year'=>$authorized['exp_year'],
                'card_type'=>$authorized['card_type'], 'bank'=>$authorized['bank'],
            ])->first();

            //if card exist return card added successful
            if ($checkCard){
                return $this->responseService->getSuccessResource(['message'=>'Card Already added']);
            }

            //if not, store the new card information
            try {

                DB::beginTransaction();

                $this->customerCard->findByColumn([
                    'customer_id'=>$this->customerAuth->authCustomer()->id, 'default' => true
                ])->update([
                    'default' => false
                ]);

                $authorized['customer_id'] = $this->customerAuth->authCustomer()->id;
                $authorized['amount_charged'] = $amount;
                $authorized['reference'] = $request->reference;
                $this->customerCard->create($authorized);

                DB::commit();

                return $this->responseService->getSuccessResource(['message'=>'Card added']);

            }catch (\Exception $e){

                report($e);
                DB::rollBack();
                return $this->responseService->getErrorResource([
                    'message' => 'OOPS!!! Something went wrong, please contact system admin '.$e->getMessage()
                ]);
            }
        }
        return $this->responseService->getErrorResource([
            "message" => 'Invalid payment'
        ]);
    } //
}
