<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FixedDepositHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'fixed_deposit_account_number' => $this['AccountNumber'],
            'amount' => $this['Amount'],
            'start_date' => Carbon::parse($this['InterestAccrualCommencementDate'])->format('d M Y'),
            'end_date' => Carbon::parse($this['InterestAccrualCommencementDate'])->addDays($this['TenureInDays'])->format('d M Y'),
            'interest_rate' => $this['interestRate'],
            'tenure_in_days' => $this['TenureInDays']
        ];
    }
}
