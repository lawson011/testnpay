<?php


namespace App\Services\BankOne\Loan;


use GuzzleHttp\Client;

class LoanServices
{

    const getLoan = '/Loan/GetLoansByCustomerId/2';

    const approveLoan = '/LoanApplication/LoanCreationApplication2/2';

    public function getLoanByCustomerCbaId(string $customerID){

        $url = env('BANK_ONE_BASE_URL').self::getLoan;

        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $response = $client->get($url.'?authtoken='.env('BANK_ONE_INSTITUTION_TOKEN').'&institutionCode='
            .env('BANK_ONE_INSTITUTION_CODE').'&CustomerId='.$customerID
        );

        return json_decode($response->getBody(),true);

    }

    /**
     * @param array $params
     * @return \Exception|mixed
     * When a loan is approved it will be created in the cba
     */
    public function approveLoan(array $params){

        try {

            $url = env('BANK_ONE_BASE_URL') . self::approveLoan;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->post($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN'),
                [
                    'body' => json_encode($params),
                ]
            );

            return json_decode($response->getBody(), true);
        }catch (\Exception $exception){
            return $exception;
        }
    }
}
