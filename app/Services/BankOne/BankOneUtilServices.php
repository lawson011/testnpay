<?php


namespace App\Services\BankOne;


use Exception;
use GuzzleHttp\Client;

class BankOneUtilServices
{

    const smsUrl = "/Messaging/SaveBulkSms/2";
    const verifyBVN = "/Account/BVN/GetBVNDetails";

    public function sendSms(array $params)
    {
        try {

            $url = env('BANK_ONE_BASE_URL').self::smsUrl;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->post($url . '?authtoken=' . env('BANK_ONE_INSTITUTION_TOKEN'),
                ['body' => json_encode($params)]
            );

            return json_decode($response->getBody(), true);
        }catch (Exception $exception){
            report($exception);
            return false;
        }
    }

    public function verifyBVN(array $params)
    {
        try {

            $url = env('BANK_ONE_THIRD_PARTY_API').self::verifyBVN;

            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->post($url,
                ['body' => json_encode($params)]
            );

            return json_decode($response->getBody(), true);
        }catch (Exception $exception){
            report($exception);
            return false;
        }
    }
}
