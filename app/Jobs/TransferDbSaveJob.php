<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Transaction\TransactionInterface;
use Illuminate\Support\Facades\Cache;

class TransferDbSaveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3,$transaction,$params;

    /**
     * Create a new job instance.
     *
     * @param TransactionInterface $transaction
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param TransactionInterface $transaction
     * @return void
     */
    public function handle(TransactionInterface $transaction)
    {
        logger('------ Queue dispatch for transaction save --------');
            $data = $transaction->create($this->params);
        logger($data);
    }
}
