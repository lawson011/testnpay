<?php

namespace App\Jobs;

use App\Repositories\Beneficiary\BeneficiaryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveBeneficiaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;

    /**
     * SaveBeneficiaryJob constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param BeneficiaryInterface $beneficiary
     * @return void
     */
    public function handle(BeneficiaryInterface $beneficiary)
    {
        logger('---------------------Beneficiary Queue Started---------------------------');
        logger($this->params);
            $beneficiary->create($this->params);
        logger('---------------------Beneficiary Queue End-------------------------------');
    }
}
