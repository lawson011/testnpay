<?php


namespace App\Services\Etranzact;

use App\Exceptions\ApplicationProcessFailedException;
use App\Services\BankOne\ThirdPartyApiService\Account\Traits\AccountValidation;
use App\Services\ResponseService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class BaseService
{
    use AccountValidation;

    protected $response;
    protected $terminal;
    protected $reference;
    protected $pin;
    protected $destination;
    protected $alias;
    protected $name;
    protected $description;


    /**
     * CategoryService constructor.
     * @param ResponseService $response
     */
    public function __construct(ResponseService $response)
    {
        $this->response     = $response;
        $this->terminal     = config('etrazanct.terminal_id');
        $this->pin          = config('etrazanct.pin');
    }

    /**
     * @param null $body
     * @return mixed
     * @throws ApplicationProcessFailedException
     */
    public function post($body = null)
    {
        $client = new Client(); // Create the Guzzle Client

        $response = $client->post(config('etrazanct.base_url'), [
            'headers' => [
                'SOAPAction'=>config('etrazanct.base_url'),
                'Content-Type'=>'text/xml',
                
            ],
            'verify' => false,
            'debug' => true,
            'body'=>$body
        ]);

        $data = $response->getBody()->getContents();

        logger()->info('Etrazanct response '.$data);

        return convertSoapToArray($data);
    }
}
