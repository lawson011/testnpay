<?php


namespace App\Services\BankOne\ThirdPartyApiService\Transfer;

use App\Exceptions\AccountValidationException;
use App\Exceptions\ApplicationProcessFailedException;
use App\Mail\SendTransactionReciept;
use App\Services\BankOne\BaseService;
use App\Services\BankOne\ThirdPartyApiService\Account\Traits\AccountValidation;
use App\Jobs\TransferDbSaveJob;
use App\Jobs\SendReceiptJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Jobs\LocalTransferServiceJob;

class LocalTransferService extends BaseService
{
    use AccountValidation;

    public const localTransfer = '/Transfer/LocalFundsTransfer';
    public $responseData = [];

    public function sendPost($body)
    {
        $this->url = config('bankone.thirdparty-api.base-url').self::localTransfer;

        dataLogger([
            'statement' => 'Local Transfer'. $this->url,
            'content' => 'Transaction Details '.json_encode($body,true),
        ]);

        return  $this->post($this->url,$body,null);
    }


    /**
     * @param $body
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function send($body)
    {
        $body['Narration']   = $this->prepareNarration(
            getSpecificAccountInformation($body['ToAccountNumber'],'Name'),
            $body
        );

        $body['AuthenticationKey'] = config('bankone.nuture-mfb.institution-token');

        $this->validateAccountDetails($body);

        dispatch(new LocalTransferServiceJob($body));

        return $this->response->getSuccessResource([
            'message' => 'Transfer successful'
        ]);
    }

    /**
     * @param $params
     * @throws ApplicationProcessFailedException
     */
    public function validateAccountDetails($params):void
    {
        $userDetails = getUserAccountDetails(['account_number' => $params['FromAccountNumber']]);

        $this->runAccountValidation($userDetails,$params['Amount']);
    }


    /**
     * @param string $narration
     * @param $name
     * @param $reference
     * @return string
     */
    public function prepareNarration($name,$data): string
    {
        return " TRF FROM ".auth()->user()->first_name."  TO $name ".$data["TransactionReference"];
    }

    /**
     * @param $params
     */
    public function dispatchDatabaseProcess($params): void
    {
        $identifier = auth()->user()->id;

        $data = [
            'customer_id' => $identifier,
            'transaction_reference' => $params['TransactionReference'],
            'amount' => $params['Amount'],
            'sender_account_number' => $params['FromAccountNumber'],
            'receiver_account_number' => $params['ToAccountNumber'],
            'narration' => $params['Narration'],
            'transaction_type' => 'local',
            'device' => $params['device'],
            'channel' => $params['channel'],
            'status' => 1
        ];

        dispatch(new TransferDbSaveJob($data));
    }

    /**
     * @param $params
     */
    public function dispatchReceipt($params):void
    {
        $fullname = auth()->user()->full_name;

        $email = auth()->user()->email;

        $data = [
            'transaction_reference' => $params['TransactionReference'],
            'transaction_date' => now()->format('Y-m-d'),
            'source_account_name' => $fullname,
            'source_account_number' => $params['FromAccountNumber'],
            'narration' => $params['Narration'],
            'receivers_account_name' => getSpecificAccountInformation($params['ToAccountNumber'],'Name'),
            'receivers_account_number' => $params['ToAccountNumber'],
            'receivers_bank' => 'NutureMfb',
            'amount' => $params['Amount'],
            'email' => $email
        ];

        dispatch(new SendReceiptJob($data));
    }
}
