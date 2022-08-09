<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LoanHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this['ID'],
            'amount' => ($this['LoanAmount']/100), //convert kobo to naira
            'rate' => $this['InterestRate'],
            'loan_status' => $this['RealLoanStatus'],
            'start_date' => formatDate($this['InterestAccrualCommenceDate'])->format('d M Y'),
            'end_date' => Carbon::parse($this['InterestAccrualCommenceDate'])->addDays($this['LoanCycle'])->format('d M Y'),
        ];
    }
}
