<?php

namespace App\Jobs;

use App\Exceptions\ApplicationProcessFailedException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\BankOne\ThirdPartyApiService\Transfer\LocalTransferService;

class LocalTransferServiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $body;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @throws ApplicationProcessFailedException
     */
    public function handle()
    {
        $localTransferService = app(LocalTransferService::class);

        $response = $localTransferService->sendPost($this->body);

        if($response['IsSuccessFul']){
            $localTransferService->dispatchDatabaseProcess($this->body);
            $localTransferService->dispatchReceipt($this->body);

            dataLogger([
                'statement' => 'Local Transfer successful ',
                'content' =>   $this->body['Narration'],
            ]);
        }
        else{
            throw new ApplicationProcessFailedException('Transaction failed',400);
        }
    }
}
