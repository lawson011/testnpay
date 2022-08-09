<?php


namespace App\Services\Paystack;


use App\Services\Paystack\PaystackHttpClient;
use App\Services\ResponseService;

class PaystackService
{
    const amount = '100'; //format amount to kobo

    protected $httpClient, $responseService;

    public function __construct(PaystackHttpClient $paystackHttpClient, ResponseService $responseService)
    {
        $this->httpClient = $paystackHttpClient;
        $this->responseService = $responseService;
    }

    public function resolveBvn(string $bvn){
        //check if customer bvn is valid, if valid return with bvn information
        return $this->httpClient->get(PaystackConstant::RESOLVE_BVN.$bvn);
    }

    public function resolvedAcctNo(array $params){
        //Get customer account in using account number and bank code
        $params = "?account_number=".$params['acct_no']."&bank_code=".$params['bank_code'];

        return $this->httpClient->get(PaystackConstant::RESOLVE_ACCOUNT_NUMBER_URL.$params);
    }

    public function createRecipient(array $params){
        //create transfer recipient, recipient code will be use for transfer
        $params = [
            'type' => 'nuban',
            'name' => $params['name'],
            'account_number' => $params['account_number'],
            'bank_code' => $params['bank_code']
        ];

        return $this->httpClient->post(PaystackConstant::CREATE_RECIPIENT,$params);
    }

    public function createTransfer(array $params){

        //make transfer to customer default bank account account
        $params = [
            'recipient' => $params['recipient_code'],
            'amount' => $params['amount']*self::amount,
            'source' => 'balance',
            'reason' => 'NuturePay Loan'
        ];
        return $this->httpClient->post(PaystackConstant::PAYSTACK_INITIATE_TRANSFER,$params);
    }

    public function debit(array $params){
        //using customer authorisation code to debit a card for recurring transaction
        $params = [
            'authorization_code' => $params['auth_code'],
            'amount' => $params['amount']*self::amount,
            'email' => $params['email']
        ];
        return $this->httpClient->post(PaystackConstant::CHARGE_CARD_URL,$params);
    }

    public function refund(string $transactionReference){
        $params = [
            'transaction' => $transactionReference
        ];
        return $this->httpClient->post(PaystackConstant::REFUND_BASE_URL,$params);
    }

    public function verifyTransaction(string $transactionReference){
        return $this->httpClient->get(PaystackConstant::VERIFY_TRANS_URL.$transactionReference);
    }

    public function balance(){
        //check paystack balance
        $balance = $this->httpClient->get(PaystackConstant::MINIMUM_BALANCE);

        return [
            'status' => $balance['status'],
            'balance' =>  $balance['data'][0]['balance']
            ];
    }
}
