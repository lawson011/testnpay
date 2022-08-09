<?php


namespace App\Services\BankOne\ThirdPartyApiService\Account;

use App\Exceptions\ApplicationProcessFailedException;
use App\Services\BankOne\BaseService;
use App\Http\Resources\AccountDetailsResource;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\AllAccountsResource;

class AccountEnquiryService extends BaseService
{
    public const thirdPartyApiEnquiry = '/Account/AccountEnquiry';
    public const swaggerCustomerAccounts = '/Customer/GetCustomerInfoByCustomerID/2';

    /**
     * Swagger Apis
     * Get user account information
     * @return Response|mixed
     * @throws RequestException
     * @throws ApplicationProcessFailedException
     */
    public function getAccountInformationMe()
    {
        $url = env('BANK_ONE_BASE_URL').self::swaggerCustomerAccounts.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN').'&customerID=';

        $response = Http::withHeaders(['Content-Type' => 'application/json'])->get(
            $url.auth()->user()->cba_id
        );

        logger($response->status() . ' Account Details Call BankOne [getAccountInformationMe]');
        logger($response->body() . 'Response from bankone for account enquiry [getAccountInformationMe]');

        if ($response->status() === 200) {
            $data = json_decode($response->body(), true);

            if (is_array($data) && array_key_exists('CustomerDetails', $data)) {
                return $data;
            }

            return $response->throw();
        }

        throw new ApplicationProcessFailedException("Could not complete request", 400);
    }

    /**
     * Get the type of information needed
     * @param $information_type
     * @param null $response_type
     * @return JsonResponse|mixed
     * @throws RequestException
     * @throws ApplicationProcessFailedException
     */
    public function getPersonalInformationTypeMe($information_type, $response_type = null)
    {
        $data = empty($information_type) ? $this->getAccountInformationMe() : $this->getAccountInformationMe()[$information_type];

        if ($response_type === 'raw_data') {
            return $data;
        }

        return $this->response->getSuccessResource([
            'data' => $data
        ]);
    }

    /**
     * Get the accounts of the currently logged in user
     * Depends on getAccountInformationMe
     * @param string[Accounts,CustomerDetails] $information_type
     * @param string[raw_data] $response_type
     * @param string['SavingsOrCurrent'] $account_type
     * @return JsonResponse|mixed
     * @throws RequestException
     * @throws ApplicationProcessFailedException
     * @errorcode gAAM
     */
    public function getAllAccountsMe($information_type)
    {
        $data = $this->getPersonalInformationTypeMe($information_type, 'raw_data');

        $collection = collect($data)->where('AccountStatus', '!=', 'Closed');

        if ($collection->isEmpty()) {
            logger('-- [getAllAccountsMe] --');
            throw new ApplicationProcessFailedException('Failed to retrieve user information', 400);
        }

        return $this->response->getSuccessResource([
            'data' => AllAccountsResource::collection($collection)
        ]);
    }

    /**
     * @param $params
     * @param null $response_type
     * @return JsonResponse|mixed
     * @throws ApplicationProcessFailedException
     */
    public function getNameEnquiry($params, $response_type = null)
    {
        $response = Http::withHeaders(['Content-Type' => 'application/json'])->post(
            config('bankone.thirdparty-api.base-url') . self::thirdPartyApiEnquiry, [
            'AccountNo' => $params['account_number'],
            'AuthenticationCode' => config('bankone.nuture-mfb.institution-token'),
            'referenceID' => 'enq_'.getUniqueToken(7),
            'reason' => 'Name Check for '
        ]);

        logger($response->body().' Response from bankone for account enquiry');

        if ($response->status() !== 200) {
            logger('-- [getNameEnquiry] --');

            throw new ApplicationProcessFailedException('Failed to retrieve user account details', 400);
        }

        $data = json_decode($response->body(), true);

        if (!is_array($data)) {
            throw new ApplicationProcessFailedException('Failed to retrieve user account details !!', 400);
        }

        if (!isset($data['ResponseDescription']) || $data['ResponseDescription'] !== 'Successful') {
            throw new ApplicationProcessFailedException('Failed to retrieve user account details !!', 400);
        }

        if ($response_type === 'data') {
            return $data;
        }

        return $this->response->getSuccessResource([
            'data' => $data['Name']
        ]);
    }

    /**
     * Name,FirstName,LastName,Email,PhoneNo,Nuban,
     * Number,ProductCode,PhoneNuber,
     * BVN,AvailableBalance,LedgerBalance,
     * Status,Tier,MaximumBalance,MaximumDeposit
     * IsSuccessful,ResponseMessage,PNDStatus,
     * LienStatus,FreezeStatus,RequestStatus,
     * ResponseDescription,ResponseStatus
     *
     * @param $params
     * @param $field
     * @return mixed|null
     * @throws ApplicationProcessFailedException
     */
    public function getSpecificAccountInformation($params, $field = null)
    {
        $response = $this->getNameEnquiry($params, 'data');

        //return null if it fails
        if (!is_array($response) && $response['ResponseDescription'] !== 'Successful') {
            return null;
        }

        //general usage of this function anywhere
        if (is_null($field)) {
            return $response;
        }

        //return a specific field
        if (isset($field, $response[$field])) {
            return $response[$field];
        }

        return null;
    }

    /**
     * @param $account
     * @throws ApplicationProcessFailedException
     * @throws RequestException
     */
    public function accountBelongsToUser($account): void
    {
        $data = $this->getPersonalInformationTypeMe(
            'Accounts', 'raw_data'
        );

        $return = collect($data)->where('NUBAN', '=', $account);

        if (count($return) === 0) {
            throw new ApplicationProcessFailedException('Invalid account', 400);
        }
    }

    /**
     * Check the user has the amount
     * @param $details
     * @param $account
     * @param $amount
     * @throws ApplicationProcessFailedException
     */
    public function accountHasRequestedAmount($details, $amount)
    {
        if(!auth()->user()->is_staff && !auth()->user()->is_agent) {
            $amount += config('npay.account_minimum_balance');
        }

        if ($amount > ($details['AvailableBalance'] / 100)) {
            throw new ApplicationProcessFailedException('Account does not have the requested amount', 400);
        }
    }

    /**
     * User has limit on account
     * @param $data
     * @throws ApplicationProcessFailedException
     */
    public function accountHasLimit($data)
    {
        if (!$data['IsSuccessful']) {
            throw new ApplicationProcessFailedException('Request failed', 400);
        }

        if ($data['LienStatus'] === 'Active') {
            throw new ApplicationProcessFailedException('Lien placed on account', 400);
        }

        if ($data['PNDStatus'] === 'Active') {
            throw new ApplicationProcessFailedException('Post no debit active on account', 400);
        }

        if ($data['FreezeStatus'] === 'Active') {
            throw new ApplicationProcessFailedException('Account has been frozen', 400);
        }

        if ($data['Status'] === 'InActive') {
            throw new ApplicationProcessFailedException('Account is inactive', 400);
        }
    }
}
