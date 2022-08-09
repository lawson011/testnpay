<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApplicationProcessFailedException;
use App\Http\Controllers\Controller;
use App\Services\BankOne\ThirdPartyApiService\Account\AccountEnquiryService;
use App\Services\BankOne\ThirdPartyApiService\Account\NipAccountEnquiryService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AccountEnquiryLocalRequest;
use App\Http\Requests\AccountEnquiryInterbankRequest;

class AccountEnquiryController extends Controller
{
    public $accountEnquiry;
    public $nipAccountEnquiryService;

    /**
     * AccountEnquiryController constructor.
     * @param AccountEnquiryService $accountEnquiry
     * @param NipAccountEnquiryService $nipAccountEnquiryService
     */
    public function __construct(AccountEnquiryService $accountEnquiry, NipAccountEnquiryService $nipAccountEnquiryService)
    {
        $this->accountEnquiry = $accountEnquiry;
        $this->nipAccountEnquiryService = $nipAccountEnquiryService;
    }

    /**
     * Retrieve data from api for user
     * default params is me
     * @return JsonResponse|mixed
     * @throws RequestException
     * @throws ApplicationProcessFailedException
     */
    public function me()
    {
        return $this->accountEnquiry->getAllAccountsMe('Accounts');
    }

    /**
     * @param AccountEnquiryLocalRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function query(AccountEnquiryLocalRequest $request)
    {
        return $this->accountEnquiry->getNameEnquiry($request->validated(),null);
    }

    /**
     * @param AccountEnquiryInterbankRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function nipQuery(AccountEnquiryInterbankRequest $request)
    {
        return $this->nipAccountEnquiryService->getAccountInformation($request->validated());
    }
}
