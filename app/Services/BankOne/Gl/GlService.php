<?php


namespace App\Services\BankOne\Gl;

use App\Exceptions\ApplicationProcessFailedException;
use Illuminate\Support\Facades\Http;

class GlService
{
    public $url;

    public function post($url, $data)
    {
        $endpoint = config('bankone.thirdparty-api.base-url').$url;

        $response = Http::post($endpoint, $data);

        logger('Gl Transaction ' . $response->body());
        logger('endpoint '. $endpoint);

        if ($response->successful()) {
            $data = json_decode($response->body(), true);

            if ($data['IsSuccessful'] && $data["Status"] === "Successful") {
                return $data['TransactionReference'];
            }

            throw new ApplicationProcessFailedException('Transaction failed GL', 400);
        }

        throw new ApplicationProcessFailedException('Transaction failed GL', 400);
    }

    /**
     * @param $url
     * @param $data
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function debit($data)
    {
        $this->url = '/Transactions/Debit';

        $request = [
            'NibssCode' => config('bankone.nuture-mfb.nibbs_code'),
            'AccountNumber' => $data['account'],
            'Amount' => $data['amount'],
            'RetrievalReference' => 'GL' . $data['reference'],
            'Narration' => $data['narration'],
            'GLCode' => config('npay.gl_bills'),
            'Token' => config('bankone.nuture-mfb.institution-token'),
        ];

        if(isset($data['category']) && $data['category'] !== '1'){
            $request['Fee'] = (double)config('npay.bills_fee');
        }

        return $this->post($this->url, $request);
    }
}
