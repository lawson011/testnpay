<?php


namespace App\Services;

use App\Exceptions\ApplicationProcessFailedException;
use App\Repositories\Transaction\TransactionInterface;
use App\Http\Resources\TransactionHistoryResource;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TransactionHistoryService extends ResponseService
{
    public $transaction;
    public $path = '/Account/GetTransactions/2?';

    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Find the reference
     * @param array $params
     * @return JsonResponse
     * @throws RequestException
     */
    public function fetch(array $params)
    {
        $url = config('bankone.base_url.base_url') . $this->path;

        $query = Arr::query($params);

        $response = Http::withHeaders(['Content-Type' => 'application/json'])->get($url . $query);

        if ($response->status() !== 200) {
            $response->throw();
        }

        $data = $response->body();

        dataLogger([
            'statement' => '------- Transaction History -----------',
            'content' => $data
        ]);

        return json_decode($data, true);
    }

    /**
     * @param $validated
     * @return JsonResponse
     * @throws ApplicationProcessFailedException
     * @throws RequestException
     */
    public function range($validated)
    {
        //validated range for the development
        if (Carbon::parse($validated['start_date'])->diffInDays($validated['end_date']) > 90) {
            throw new ApplicationProcessFailedException('Invalid range selected, default range is 3 months', 412);
        }

        $params = [
            'authtoken' => config('bankone.nuture-mfb.institution-token'),
            'accountNumber' => $validated['account_number'],
            'fromDate' => $validated['start_date'],
            'toDate' => $validated['end_date'],
            'numberOfItems' => config('npay.account_histroy_limit')
        ];

        $collection = $this->fetch($params);

        if (is_array($collection) && $collection['IsSuccessful']) {
            if (!empty($collection['Message'])) {
                return $this->getSuccessResource([
                    'data' => TransactionHistoryResource::collection(collect($collection['Message']))
                ]);
            }

            throw new ApplicationProcessFailedException('Transaction history empty', 400);
        }

        throw new ApplicationProcessFailedException('Transaction history empty', 400);
    }
}
