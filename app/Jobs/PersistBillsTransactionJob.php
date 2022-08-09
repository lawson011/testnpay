<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BillTransaction;
use Illuminate\Support\Facades\DB;

class PersistBillsTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //pass data to the model here
        DB::beginTransaction();
            BillTransaction::create([
                'billers_category_id' => $this->data['details']['category'],
                'reference' => $this->data['reference'],
                'account' => $this->data['account'],
                'amount' => $this->data['amount'],
                'trx_reference' => $this->data['gl_reference'],
            ]);
        DB::commit();
    }
}
