<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApplicationProcessFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BvnRequest;
use App\Http\Requests\CustomerIdentityUploadRequest;
use App\Http\Requests\CustomerUtilityUploadRequest;
use App\Http\Requests\RequestForCardRequest;
use App\Http\Requests\SignatureUploadRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerAccountsResource;
use App\Http\Resources\IdentityCardTypeResource;
use App\Http\Resources\UtilityTypeResource;
use App\Jobs\UploadSignatureToCBAJob;
use App\Repositories\CardRequest\CardRequestInterface;
use App\Repositories\CustomerAuth\CustomerAuthInterface;
use App\Repositories\CustomerBioData\CustomerBioDataInterface;
use App\Repositories\CustomerIdentityCard\CustomerIdentityCardInterface;
use App\Repositories\CustomerUtility\CustomerUtilityInterface;
use App\Services\BankOne\BankOneUtilServices;
use App\Services\BankOne\CustomerAccount\AccountServices;
use App\Services\BankOne\ThirdPartyApiService\Account\AccountEnquiryService;
use App\Services\ResponseService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    protected $responseService, $customerUtility, $customerIdentityCard, $bankOneUtilServices,
        $accountServices, $customerAuth, $customerBioData, $cardRequest;

    public function __construct(ResponseService $responseService, CustomerUtilityInterface $customerUtility,
                                CustomerAuthInterface $customerAuth, CustomerIdentityCardInterface $customerIdentityCard,
                                BankOneUtilServices $bankOneUtilServices, CustomerBioDataInterface $customerBioData,
                                AccountServices $accountServices, CardRequestInterface $cardRequest)
    {
        $this->responseService = $responseService;
        $this->customerUtility = $customerUtility;
        $this->customerIdentityCard = $customerIdentityCard;
        $this->bankOneUtilServices = $bankOneUtilServices;
        $this->customerAuth = $customerAuth;
        $this->customerBioData = $customerBioData;
        $this->accountServices = $accountServices;
        $this->cardRequest = $cardRequest;
    }

    public function index()
    {
        $data = Auth::user();
        $data->token = '';
        $resource = new CustomerResource($data);
        return $this->responseService->getSuccessResource(['data' => $resource]);
    }

    public function termsCondition(Request $request)
    {
        $request->user()->update([
            'terms_and_condition' => true
        ]);
        return $this->responseService->getSuccessResource();
    }

    public function updateBVN(BvnRequest $request)
    {

        $bvnParams = [
            'BVN' => $request->bvn,
            'token' => env('BANK_ONE_INSTITUTION_TOKEN')
        ];

        //get bvn information from bankone api
        $bvn = $this->bankOneUtilServices->verifyBVN($bvnParams);

        if ($bvn['isBvnValid'] != true) {

            return $this->responseService->getErrorResource([
                "message" => 'Invalid BVN'
            ]);
        }

        //Check if customer registered name correspond with bvn name
        $firstName = Str::contains($this->customerAuth->authCustomer()->full_name, strtoupper($bvn['bvnDetails']['FirstName']));

        $lastName = Str::contains($this->customerAuth->authCustomer()->full_name, strtoupper($bvn['bvnDetails']['LastName']));

        if (!$firstName || !$lastName) {
            return $this->responseService->getErrorResource([
                "message" => "BVN name does not correspond with registered name",
            ]);
        }

        //update biodata
        $this->customerBioData->findByColumn([
            ['customer_id', '=', $this->customerAuth->authCustomer()->id]
        ])->update(
            [
                'bvn' => $bvn['bvnDetails']['BVN'],
                'bvn_dob' => $bvn['bvnDetails']['DOB'],
                'bvn_phone' => $bvn['bvnDetails']['phoneNumber']
            ]
        );

        return $this->responseService->getSuccessResource([
            "message" => 'BVN update successful'
        ]);
    }

    public function listUtility()
    {
        $data = getAllUtilityType();
        $resource = UtilityTypeResource::collection($data);
        return $this->responseService->getSuccessResource([
            'data' => $resource
        ]);
    }

    public function listIdentityCard()
    {
        $data = getAllIdentityCardType();
        $resource = IdentityCardTypeResource::collection($data);
        return $this->responseService->getSuccessResource(['data' => $resource]);
    }

    public function uploadUtility(CustomerUtilityUploadRequest $request)
    {

        try {

            DB::beginTransaction();

            //update old utility to false
            $this->customerUtility->updateRow(['customer_id' => Auth::id()], ['active' => false]);

            //add new utility
            $this->customerUtility->create($request->all());

            DB::commit();

            return $this->responseService->getSuccessResource(['message' => 'Successful']);

        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error('Failed To Upload User Utility', formatLogResponse($exception));
            return $this->responseService->getSuccessResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin ' . $exception->getMessage()
            ]);
        }
    }

    public function uploadIdentityCard(CustomerIdentityUploadRequest $request)
    {
        try {
            DB::beginTransaction();
            //update old identity card to false
            $this->customerIdentityCard->updateRow(['customer_id' => Auth::id()], ['active' => false]);

            //add new identity card
            $this->customerIdentityCard->create($request->all());
            DB::commit();
            return $this->responseService->getSuccessResource(['message' => 'Successful']);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Failed To Upload User Identity card', formatLogResponse($exception));
            return $this->responseService->getSuccessResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin ' . $exception->getMessage()
            ]);
        }
    }

    /**
     * Upload customer signature
     *
     * @param SignatureUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSignature(SignatureUploadRequest $request)
    {
        try {
            DB::beginTransaction();
            //update old identity card to false
            $signature = $this->customerBioData->findByColumn([
                ['customer_id', '=', Auth::id()]
            ])->first();

            if (!$signature) {
                return $this->responseService->getSuccessResource([
                    'message' => 'Invalid Customer'
                ]);
            }

//            if ($signature->signature){
//                return $this->responseService->getSuccessResource([
//                    'message' => 'You already uploaded your signature'
//                ]);
//            }

            $photo = Storage::putFile('public\signature', new File($request->signature)); //save image

            // saved photo absolute path
            $signature->signature = asset(Storage::url(str_replace('public', '', $photo)));
            $signature->save();

            dispatch(new UploadSignatureToCBAJob($signature));

            DB::commit();

            return $this->responseService->getSuccessResource(['message' => 'Successful']);

        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error('Failed To Upload User signature', formatLogResponse($exception));
            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! Something went wrong, please contact system admin'
            ]);
        }
    }

    public function customerSavingsOrCurrentAccounts()
    {
        $customer = $this->accountServices->customerAccounts($this->customerAuth->authCustomer()->cba_id);

        $resource = collect($customer['Accounts'])->where('AccountType', '=', 'SavingsOrCurrent');

        return $this->responseService->getSuccessResource([
            'data' => CustomerAccountsResource::collection($resource)
        ]);

    }

    public function customerAccounts()
    {
        $customer = $this->accountServices->customerAccounts($this->customerAuth->authCustomer()->cba_id);

        $resource = collect($customer['Accounts']);

        return $this->responseService->getSuccessResource([
            'data' => CustomerAccountsResource::collection($resource)
        ]);
    }

    public function active(Request $request)
    {
        $user = $request->user();

        if ($user->is_active) {
            return $this->responseService->getSuccessResource([
                'message' => 'Account already active'
            ]);
        }

        $active = false;

        if ($user->is_staff) {
            $active = true;
        }

        if ($user->is_agent) {
            $active = true;
        }

        if (! $user->is_staff && ! $user->is_agent) {

            $accountInfo = (new AccountEnquiryService($this->responseService));

            try {
                $accounts = $accountInfo->getAccountInformationMe();
            } catch (ApplicationProcessFailedException $e) {
                logger($e);
            } catch (RequestException $e) {
                logger($e);
            }

            //Check customer account with more than minimum balance
            $checkAccount = collect($accounts['Accounts'])
                ->where('AccountType', '=', 'SavingsOrCurrent');

            foreach ($checkAccount as $account) {
                try {
                    $balance = $accountInfo->getSpecificAccountInformation([
                        'account_number' => $account['NUBAN']
                    ])['LedgerBalance'];
                } catch (ApplicationProcessFailedException $e) {
                    logger($e);
                }

                if ($balance >= config('npay.account_minimum_balance')) {
                    $active = true;
                }
            }

        }

        if ($active) {
            $user->is_active = true;
            $user->save();
            return $this->responseService->getSuccessResource([
                'message' => 'Staff account active'
            ]);
        }

        return $this->responseService
            ->getErrorResource([
            'message' => 'Insufficient amount'
            ]);
    }

    public function requestForCard(RequestForCardRequest $request)
    {
        //check if customer has already requested by using card request status
        $check = $this->cardRequest->findByColumn([
            ['customer_id', '=', $this->customerAuth->authCustomer()->id]
        ])->first();

        //check if status is processing or approved
        if (($check && $check->card_request_status_id == cardRequestStatusByName('Processing')->id)
            || ($check && $check->card_request_status_id == cardRequestStatusByName('Approved')->id)) {

            return $this->responseService->getErrorResource([
                'message' => 'OOPS!!! You recently requested for a card'
            ]);
        }

        //store the request
        $this->cardRequest->create($request->all());

        return $this->responseService->getSuccessResource([
            'message' => 'Card request successful'
        ]);
    }
}
