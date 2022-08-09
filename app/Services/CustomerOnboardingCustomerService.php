<?php


namespace App\Services;


use App\Repositories\Beneficiary\BeneficiaryInterface;
use App\Repositories\CustomerOnboardingCustomer\CustomerOnboardingCustomerInterface;
use App\Repositories\CustomerRegistrationSetting\CustomerRegistrationSettingInterface;
use App\Services\BankOne\CustomerAccount\AccountServices;
use Illuminate\Support\Facades\Auth;

class CustomerOnboardingCustomerService
{
    protected $responseService, $customerOnboardingCustomer;

    public function __construct(ResponseService $responseService, CustomerOnboardingCustomerInterface $customerOnboardingCustomer)
    {
        $this->responseService = $responseService;
        $this->customerOnboardingCustomer = $customerOnboardingCustomer;
    }

    /**
     * Store customer onboardidng customer processes
     *
     * @param $request
     *
     * @return mixed \Illuminate\Http\JsonResponse
     */
    public function store($request)
    {
        $customer = $request->user();

        $account_service = (new AccountServices());

        //Check if account number is for the auth customer
        $account = $account_service->getCustomerByAccountNumber($request->input('account_number'));
        if ($account['customerID'] != $customer->cba_id) {
            return $this->responseService->getErrorResource([
                'message' => 'Invalid account number'
            ]);
        }

        //Check if customer balance is sufficient
        $balance = (getUserAccountDetails(['account_number' => $request->input('account_number')])['AvailableBalance'] / 100);

        if (! $customer->is_staff && ! $customer->is_agent){
            if (($balance - config('npay.account_minimum_balance')) <= $request->input('amount')) {
                return $this->responseService->getErrorResource([
                    'message' => 'Insufficient account balance'
                ]);
            }
        }

        //Create account in CBA
        $createAccountInCBA = $this->createAccountInCBA($request);

        //Create the customer
        $this->create($request, $createAccountInCBA);

        if ((double)$request->input('amount') > 0) {
            //Transfer fund
            $this->transferFund($request, $createAccountInCBA);
        }

        //Save beneficiary
        if ($request->input('save_beneficiary')) {
            $this->saveBeneficiary($request, $createAccountInCBA);
        }

        return $this->responseService->getSuccessResource([
            'message' => $createAccountInCBA['AccountNumber']
        ]);
    }

    /**
     * Store new beneficiary
     *
     * @param $request
     * @param $createAccountInCBA
     */
    private function saveBeneficiary($request, $createAccountInCBA)
    {
        $beneficiary = [
            'customer_id' => Auth::id(),
            'customer_name' => "{$request->input('first_name')} {$request->input('last_name')}",
            'bank_code' => 0,
            'bank_name' => 'Nuture MFB',
            'account_number' => $createAccountInCBA['AccountNumber']
        ];
        
        app(BeneficiaryInterface::class)->create($beneficiary);
    }

    /**
     * Create customer account in CBA
     *
     * @param $request
     * @return mixed
     */
    private function createAccountInCBA($request)
    {
        $regSettings = app(CustomerRegistrationSettingInterface::class)->findByColumn([
            ['active', '=', true]
        ])->first();

        $bankOneParams = [
            'TransactionTrackingRef' => "npay/{$request->input('account_number')}-" . $request->input('phone'),
            'AccountOpeningTrackingRef' => "npay/{$request->input('account_number')}-" . $request->input('phone'),
            'ProductCode' => $regSettings->product_code,
            'LastName' => strtoupper($request->input('last_name')),
            'OtherNames' => strtoupper($request->input('first_name')),
            'FullName' => "{$request->input('last_name')} {$request->input('first_name')}",
            'PhoneNo' => $request->input('phone'),
            'Email' => $request->input('email') ?? null,
            'ReferralPhoneNo' => Auth::user()->phone,
            'ReferralName' => Auth::user()->full_name,
            'HasSufficientInfoOnAccountInfo' => true,
            'AccountInformationSource' => 0,
            'NotificationPreference' => 3,
            'AccountOfficerCode' => $regSettings->account_officer_code
        ];

        //Create account in cba
        $account_service = (new AccountServices());
        return $account_service->createAccount($bankOneParams)['Message'];
    }

    /**
     * Create account on customer onboarding table
     *
     * @param $request
     * @param $createAccountInCBA
     */
    private function create($request, $createAccountInCBA)
    {
        $params = [
            'customer_id' => Auth::id(),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email') ?? null,
            'phone' => $request->input('phone'),
            'nuban' => $createAccountInCBA['AccountNumber'],
            'cba_id' => $createAccountInCBA['CustomerID'],
            'amount' => $request->input('amount'),
        ];

        $this->customerOnboardingCustomer->create($params);
    }

    /**
     * Local transfer to customer account
     *
     * @param $request
     * @param $createAccountInCBA
     */
    private function transferFund($request, $createAccountInCBA)
    {
        $data['TransactionReference'] = 'tl' . auth()->user()->id . getUniqueToken(4);
        $data['Amount'] = (double)$request->input('amount');
        $data['channel'] = $request->server('HTTP_PLATFORM');
        $data['device'] = $request->server('HTTP_DEVICE_ID');
        $data['FromAccountNumber'] = $request->input('account_number');
        $data['ToAccountNumber'] = $createAccountInCBA['AccountNumber'];
        $data['Narration'] = $request->input('narration');
        $data['Amount'] = $request->input('amount');

        localTransfer($data);
    }
}
