<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApplicationProcessFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyBillsRequest;
use App\Services\Etranzact\BillService;
use App\Http\Requests\PayBillsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class BillController extends Controller
{
    public $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    public function index()
    {
        return Http::get(config("ips.bills.list"));
    }

    /**
     * @param VerifyBillsRequest $request
     * @return \Illuminate\Http\Client\Response
     */
    public function verify(VerifyBillsRequest $request)
    {
        return Http::post(config("ips.bills.verify"),$request->validated());
    }

    /**
     * @param PayBillsRequest $request
     * @return JsonResponse
     * @throws ApplicationProcessFailedException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function pay(PayBillsRequest $request)
    {
        return $this->billService->pay($request->validated());
    }
}
