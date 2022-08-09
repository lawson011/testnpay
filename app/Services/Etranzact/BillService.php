<?php


namespace App\Services\Etranzact;

use App\Exceptions\ApplicationProcessFailedException;

use App\Services\BankOne\ThirdPartyApiService\Account\AccountEnquiryService;
use App\Services\Etranzact\Traits\BillDetailTrait;
<<<<<<< HEAD
use App\Services\Etranzact\Traits\BillServiceAirtimeTrait;
use App\Services\Etranzact\Traits\BillServiceBillTrait;
use App\Services\Etranzact\Traits\BillsVerificationSoapTrait;
use App\Services\Etranzact\BillsVerificationService;
use App\Jobs\SendBillsRequestjob;
=======
>>>>>>> ac6e7708b07dd42c307657e120d1a340396f1ff3
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;


class BillService
{
    public $response;

    /**
     * BillService constructor.
     * @param ResponseService $response
     */
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    /**
     * @param $data
     * @return JsonResponse
     * @throws ApplicationProcessFailedException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function pay($data)
    {
<<<<<<< HEAD
        //prepare transaction details here
        $data['details'] = $this->processDetails($data['slug']);
        $data['reference'] = getUniqueToken(7);
        $data['narration'] = "Transaction " . $data['details']['billers']
            .' ' . $data['details']['operation'] . ' ' . $data['account'] . ' for ' . $data['destination'];

        $this->validateTransaction($data);

        //$data['gl_reference'] = $this->processTransaction($data);
        $data['gl_reference'] = getUniqueToken(7);


        if (!empty($data['gl_reference'])) {
            
            dispatch(new PersistBillsTransactionJob($data))->delay(now()->addMinutes(2));

            return $this->process($data);
        }

        throw new ApplicationProcessFailedException('Transaction failed', 400);
    }

    /**
     * Process debit transaction
     * @param $data
     * @return mixed
     */
    public function processTransaction($data)
    {
        return debitGl($data);
    }

    /**
     * Process request by category
     * @param $data
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function process($data)
    {
        switch ($data['details']["category"]) {
            case 1 :
                return $this->airtime($data);
                break;
            case 2 :
                return $this->bills($data);
                break;
            default:
                throw new ApplicationProcessFailedException('Failed to get category ', 400);
                break;
        }
    }

    /**
     * Retrieve response from xml response
     * @param array $data
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function getData(array $data)
    {
        if (is_array($data) && isset($data['ns2processResponse']) && $data['ns2processResponse']['response']['error'] === '0') {
=======
        $details = app(AccountEnquiryService::class)->getSpecificAccountInformation([
            'account_number' => $data['account']
        ]);
>>>>>>> ac6e7708b07dd42c307657e120d1a340396f1ff3

        //account belongs to the user
        app(AccountEnquiryService::class)->accountBelongsToUser($data['account']);
        //account has the requested amount
        app(AccountEnquiryService::class)->accountHasRequestedAmount($details, $data['amount']);
        //account has limits
        app(AccountEnquiryService::class)->accountHasLimit($details);

        $data['reference']      = getUniqueToken(7);
        $data['narration']      = "Transaction ".' for '. $data['destination'];
        $data['gl_reference']   = debitGl($data);
        $data['customer_id']    = auth()->user()->id;

        if (!empty($data['gl_reference']))
        {
            dispatch(function () use($data) {
                Http::post(config("ips.bills.pay"),$data);
            })->delay(now()->addMinutes(5));

            return $this->response->getSuccessResource([
                'message' => 'Transaction processing'
            ]);
        }

        throw new ApplicationProcessFailedException('Transaction failed', 400);
    }
}
