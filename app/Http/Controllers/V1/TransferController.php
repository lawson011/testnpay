<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AccountValidationException;
use App\Exceptions\ApplicationProcessFailedException;
use App\Http\Controllers\Controller;
use App\Services\BankOne\ThirdPartyApiService\Transfer\LocalTransferService;
use App\Services\BankOne\ThirdPartyApiService\Transfer\InterBankTransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NipTransferRequest;
use App\Http\Requests\LocalTransferRequest;

use Illuminate\Support\Str;


class TransferController extends Controller
{
    public $localTransferService;
    public $interBankTransferService;

    /**
     * TransferController constructor.
     * @param LocalTransferService $localTransferService
     * @param InterBankTransferService $interBankTransfer
     */
    public function __construct(
        LocalTransferService $localTransferService,
        InterBankTransferService $interBankTransfer
    )
    {
        $this->localTransferService = $localTransferService;
        $this->interBankTransferService = $interBankTransfer;
    }

    /**
     * @param LocalTransferRequest $request
     * @return JsonResponse
     */
    public function localTransfer(LocalTransferRequest $request)
    {
        $validated = $request->validated();

        $validated['TransactionReference'] = 'tl'.auth()->user()->id.getUniqueToken(4);
        $validated['Amount'] = (double)$validated['Amount'];
        $validated['channel'] = $request->server('HTTP_PLATFORM');
        $validated['device'] = $request->server('HTTP_DEVICE_ID');

        return $this->localTransferService->send($validated);
    }

    /**
     * @param NipTransferRequest $request
     * @return JsonResponse
     * @throws AccountValidationException
     * @throws ApplicationProcessFailedException
     */
    public function interBankTransfer(NipTransferRequest $request)
    {
        $validated = $request->validated();

        $validated['channel'] = $request->server('HTTP_PLATFORM');
        $validated['device'] = $request->server('HTTP_DEVICE_ID');

        return $this->interBankTransferService->send($validated);
    }
}
