<?php


namespace App\Services\BankOne;


use App\Exceptions\ApplicationProcessFailedException;
use App\Services\ResponseService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BaseService
{
    protected $url;
    protected $response;

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
     * @param $log
     * @return mixed
     */
    public function get($url)
    {
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])->get($url);

            logger($response->body());

            if ($response->status() !== 200) {
                $response->throw();
            }

            $data = json_decode($response->body(), true);

            return $data;
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return $this->response->getErrorResource([
                'message' => 'Service unavailable please try again'
            ]);
        }
    }

    /**
     * @param $url
     * @param null $body
     * @param null $params
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function post($url, $body = null, $params = null)
    {
        try {
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $response = $client->post($url, [
                'body' => json_encode($body),
                'form_params' => $params
            ]);

            dataLogger([
                'statement' => 'Local Transfer' . $url,
                'content' => 'Transaction Details ' . $response->getBody()
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return $this->response->getErrorResource([
                'message' => $e->getMessage()
            ]);
        }

    }
}


