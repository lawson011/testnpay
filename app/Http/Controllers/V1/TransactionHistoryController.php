<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRangeRequest;
use App\Repositories\Transaction\TransactionInterface;
use App\Services\TransactionHistoryService;

class TransactionHistoryController extends Controller
{
    public $transaction;
    public $transactionHistoryService;

    public function __construct(
        TransactionInterface $transaction,
        TransactionHistoryService
        $transactionHistoryService)
    {
        $this->transaction = $transaction;
        $this->transactionHistoryService = $transactionHistoryService;
    }

    /**
     * @param TransactionRangeRequest $request
     * @return JsonResponse
     * @throws \App\Exceptions\ApplicationProcessFailedException
     */
    public function range(TransactionRangeRequest $request){

        $validated = $request->validated();

        return $this->transactionHistoryService->range($validated);
    }
}


