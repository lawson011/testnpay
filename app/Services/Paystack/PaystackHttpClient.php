<?php

namespace App\Services\Paystack;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;

class PaystackHttpClient{

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($url)
    {
        try
        {
            $response = $this->client->request('GET',$url,['headers' => $this->headers()]);
            return json_decode($response->getBody(),true);
        }
        catch(ConnectException $exception)
        {
            logger()->error($exception->getMessage());
            return json_decode($exception->getResponse()->getBody(),true);
        }
        catch(ClientException $exception)
        {
            logger()->error($exception->getMessage());
            return json_decode($exception->getResponse()->getBody(),true);
        }
        catch(RequestException $exception)
        {
            logger()->error($exception->getMessage());
            return json_decode($exception->getResponse()->getBody(),true);
        }
        catch(ServerException $exception)
        {
            logger()->error($exception->getMessage());
            return json_decode($exception->getResponse()->getBody(),true);
        }
        catch (\Exception $exception){
            logger()->error($exception->getMessage());
            return json_decode($exception->getResponse()->getBody(),true);
        }
    }

    protected function headers()
    {
        return [
            "Authorization"=>'Bearer '.$this->paystackConstants()->getSecretKey()
        ];
    }

    protected function paystackConstants(){
        return new PaystackConstant();
    }

    public function delete($url,array $attributes)
    {

    }

    public function put($url,array $attributes)
    {

    }

    public function post(string $url,array $attributes){
        logger()->info(json_encode($this->headers()));

        try{
            $response = $this->client->request('POST',$url,[
                'json'=> $attributes,
                'headers' => $this->headers()
            ]);
            return json_decode($response->getBody(),true);
        }
        catch(ConnectException $exception)
        {
            return json_decode($exception->getResponse()->getBody(),true);
        }
        catch(ClientException $exception)
        {
            return json_decode($exception->getResponse()->getBody(),true);
        }

    }
}
