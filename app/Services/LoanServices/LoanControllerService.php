<?php


namespace App\Services\LoanServices;


use App\Http\Resources\LoanHistoryResource;
use App\Http\Resources\LoanResource;
use App\Http\Resources\LoanSettingResource;
use App\Models\Customers\Customer;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\Loan\LoanInterface;
use App\Services\BankOne\Loan\LoanServices;
use App\Services\Paystack\PaystackService;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class LoanControllerService
{
    protected $responseService, $paystackService, $customerAuth, $loan, $loanServices;

    public function __construct(ResponseService $responseService, PaystackService $paystackService,
                                CustomerAuthInterface $customerAuth, LoanInterface $loan, LoanServices $loanServices)
    {
        $this->responseService = $responseService;
        $this->paystackService = $paystackService;
        $this->customerAuth = $customerAuth;
        $this->loan = $loan;
        $this->loanServices = $loanServices;
    }

    /**
     * Customer applying for loan
     *
     * @param array $params
     *
     * @return JsonResponse
     */
    public function apply(array $params): JsonResponse
    {
        $loanSetting = getLoanSettingByColumn(['id'=>decrypt($params['loan_setting_id'])])->first();
        if (! $loanSetting) {
            return $this->responseService->getErrorResource([
                "message" => "Oops!!! Invalid Loan Setting"
            ]);
        }
        $params['loanSetting'] = $loanSetting;
        // Check if user has loan awaiting approval
        $customer = $this->customerAuth->authCustomer();

        $loan = $this->loan->findByColumn([
            ['customer_id','=',$customer->id],
            ['loan_status_id','=',loanStatusByName('Awaiting Approval')->id]
        ])->first();

        if ($loan){
            return $this->responseService->getErrorResource([
                "message" => "Oops!!! You already have a loan awaiting approval"
            ]);
        }
        $getLoan = $this->loanServices->getLoanByCustomerCbaId($customer->cba_id);
        // check if applicant has an active loan
        $activeLoan = collect($getLoan['Message'])->where('RealLoanStatus','==','Active');

        if (count($activeLoan) > 0){
            return $this->responseService->getErrorResource([
                "message" => "Oops!!! You have an active loan"
            ]);
        }
        // save the loan information to db incase of any interruption, this is suppose to come after crediting
        // applicant account. Applicant can make complain if account is not credited.
        $storeLoan = $this->storeLoan($params);

        $loanResource = new LoanResource($storeLoan);
        return $this->responseService->getSuccessResource([
            'data' => $loanResource
        ]);
    }

    private function storeLoan($params){
        $loanParams['loan_status_id'] = loanStatusByName('Awaiting Approval')->id;
        $loanParams['customer_id'] = $this->customerAuth->authCustomer()->id;
        $loanParams['loan_setting_id'] = $params['loanSetting']->id;
        $loanParams['rate'] = $params['loanSetting']->rate;
        $loanParams['term'] = $params['loanSetting']->term;
        $loanParams['repay_amount'] = $params['loanSetting']->repayment_amount;
        $loanParams['amount'] = $params['loanSetting']->amount;
        $loanParams['service_charge'] = $params['loanSetting']->service_charge;
        return $this->loan->create($loanParams);
    }


    /**
     * @return JsonResponse
     * loan approved not yet repaid
     */

    public function history(){
        // login user un repaid loan
        $customer = $this->customerAuth->authCustomer();

        $getLoan = $this->loanServices->getLoanByCustomerCbaId($customer->cba_id);

        if ($getLoan['IsSuccessful'] == true){

            $resource = LoanHistoryResource::collection(collect($getLoan['Message']));
            return $this->responseService->getSuccessResource([
                'data'=> [
                    'activeAndInactive' => $resource,
                    'awaitingApproval' => $this->awaitingApproval()
                    ]
            ]);
        }
        return $this->responseService->getErrorResource([
            'message' => 'Opps!!! Something went wrong, please try again'
        ]);
    }

    public function awaitingApproval(){
        $customer = $this->customerAuth->authCustomer();

        $loan = $this->loan->findByColumn([
            ['customer_id','=',$customer->id],
            ['loan_status_id','!=',loanStatusByName('Approved')->id]
        ])->get();

        return LoanResource::collection($loan);
    }


    public function getLoanByCustomerCbaId(){

//        $getLoan = $this->loanServices->getLoanByCustomerCbaId('021900');

        $getLoan = $this->loanServices->getLoanByCustomerCbaId($this->customerAuth->authCustomer()->cba_id);
        if ($getLoan['IsSuccessful'] == true){

            $resource = LoanHistoryResource::collection(collect($getLoan['Message'])
                ->where('RealLoanStatus','==','Active'));
            return $this->responseService->getSuccessResource(['data'=> $resource]);
        }
        return $this->responseService->getErrorResource([
            'message' => 'Opps!!! Something went wrong, please try again'
        ]);
    }

    public function loanSetting(){

        $resource = LoanSettingResource::collection(getLoanSetting());

        return $this->responseService->getSuccessResource(['data'=> $resource]);
    }
}
