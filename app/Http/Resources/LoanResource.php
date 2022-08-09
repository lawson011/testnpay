<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LoanResource extends JsonResource
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
            'id' => encrypt($this->id),
            'amount' => $this->amount,
            'rate' => $this->rate,
            'loan_status' => loanStatusById($this->loan_status_id)->name,
            'repay_amount' => $this->repay_amount,
            'service_charge' => $this->service_charge,
            $this->mergeWhen($this->disbursed == true, [
                'start_date' => formatDate($this->start_date ?? $this->created_at)->format('d M Y'),
                'end_date' => formatDate($this->end_date)->format('d M Y'),
                'repay' => $this->repay,
                $this->mergeWhen($this->repay == true, [
                    'repayment_method' => ($this->repaymentMethod) ? $this->repaymentMethod->method : null,
                    'repayment_date' => formatDate($this->repayment_date)->format('d M Y'),
                ]),
            ]),
        ];
    }
}
