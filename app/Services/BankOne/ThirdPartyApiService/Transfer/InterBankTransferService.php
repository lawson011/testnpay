<?php
namespace App\Services\BankOne\ThirdPartyApiService\Transfer;

use App\Jobs\SendReceiptJob;
use App\Mail\SendTransactionReciept;
use App\Services\BankOne\BaseService;
use App\Services\BankOne\ThirdPartyApiService\Account\Traits\AccountValidation;
use App\Jobs\TransferDbSaveJob;
use App\Exceptions\AccountValidationException;
use App\Exceptions\ApplicationProcessFailedException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

use function GuzzleHttp\json_encode;

class InterBankTransferService extends BaseService
{
    use AccountValidation;

    private const interBankTransfer = '/Transfer/InterBankTransfer';
    private const appZoneAccount = '02230012010015676';
    private const phoneNumber = '09066582734';
    public $responseData = [];

    /**
     * @param $params
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function sendPost($params)
    {
        $this->url =  env('BANK_ONE_THIRD_PARTY_API').self::interBankTransfer;

        $params = [
            'Amount'                => ($params['amount'] * 100),
            'Payer'                 => $params['payer'],
            'PayerAccountNumber'    => $params['payerAccountNumber'],
            'ReceiverAccountNumber' => $params['receiverAccountNumber'],
            'ReceiverAccountType'   => $params['receiverAccountType'],
            'ReceiverBankCode'      => $params['receiverBankCode'],
            'ReceiverPhoneNumber'   => config('bankone.nuture-mfb.mobile'),
            'ReceiverName'          => $params['receiverName'],
            'Narration'             => $params['Narration'],
            'NIPSessionID'          => $params['NIPSessionID'],
            'TransactionReference'  => substr($params['NIPSessionID'],0,12),
            'Token'                 => config('bankone.nuture-mfb.institution-token'),
            'InstitutionCode'       => config('bankone.nuture-mfb.institution-code')
        ];

        dataLogger([
            'statement' => 'Interbank Transfer'. $this->url,
            'content' => 'Transaction Details'.json_encode($params,true),
        ]);

        return $this->post($this->url,$params);
    }

    /**
     * @param $params
     * @return JsonResponse
     * @throws ApplicationProcessFailedException
     */
    public function send($params)
    {
        $params['Narration'] = $this->prepareNarration(
            $params['receiverName'],$params['NIPSessionID']
        );

        $this->validateAccountDetails($params);

        $data = $this->sendPost($params);

        if($data['IsSuccessFul']){
            $this->dispatchDatabaseProcess($params);
            $this->dispatchReceipt($params);

            return $this->response->getSuccessResource([
                'message' => "Transaction Successful"
            ]);
        }

        throw new ApplicationProcessFailedException($data['ResponseMessage'] . auth()->user()->nuban,500);
    }

    /**
     * @param string $narration
     * @param $name
     * @param $reference
     * @return string
     */
    public function prepareNarration($name,$reference)
    {
        return "TRF FROM ".auth()->user()->first_name."  TO $name $reference ";
    }

    /**
     * @param $params
     * @throws ApplicationProcessFailedException
     */
    public function validateAccountDetails($params):void
    {
        $userDetails = getUserAccountDetails(['account_number' => $params['payerAccountNumber']]);

        $this->runAccountValidation($userDetails,$params['amount']);
    }

    /**
     * @param $params
     */
    public function dispatchDatabaseProcess($params): void
    {
        $identifier = auth()->user()->id;

        $data = [
            'customer_id' => $identifier,
            'transaction_reference' => $params['NIPSessionID'],
            'nip_session' => $params['NIPSessionID'],
            'amount' => $params['amount'],
            'sender_account_number' => $params['payerAccountNumber'],
            'receiver_account_number' => $params['receiverAccountNumber'],
            'narration' => $params['Narration'],
            'transaction_type' => 'nip',
            'channel' => $params['channel'],
            'device' => $params['device'],
            'bank' => $params['receiverBankCode'],
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

        $params = [
                'transaction_reference' => $params['NIPSessionID'],
                'transaction_date' => now()->format('Y-m-d'),
                'source_account_name' => $fullname,
                'source_account_number' => $params['payerAccountNumber'],
                'narration' => $params['Narration'],
                'receivers_account_name' => $params['receiverName'],
                'receivers_account_number' => $params['receiverAccountNumber'],
                'receivers_bank' => getBankByCode($params['receiverBankCode']),
                'amount' => $params['amount'],
                'email' => $email
        ];

        dispatch(new SendReceiptJob($params));
    }
}
