<?php


namespace App\Services\BankOne\Gl;

use App\Services\BankOne\ThirdPartyApiService\Account\Traits\AccountValidation;
use App\Services\ResponseService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class BaseService
{


    /**
     * CategoryService constructor.
     * @param ResponseService $response
     */
    public function __construct(ResponseService $response)
    {
        $this->response = $response;
    }

    /**
     * @param $url
     * @param null $body
     * @param $params
     * @return mixed
     */
    public function post($url,$body=null,$params=null)
    {
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        try {
            $response = $client->post($url, [
                'body' => json_encode($body),
                'form_params' => $params
            ]);

            return json_decode($response->getBody(),true);
        }
        catch(\Exception $e) {

            return $this->response->getErrorResource([
                'message' => $e->getMessage()
            ]);
        }
    }
}
