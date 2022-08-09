<?php

namespace App\Http\Resources;

use App\Models\LoanServiceCharge;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanSettingResource extends JsonResource
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
            'repayment_amount' => $this->repayment_amount,
            'rate' => $this->rate,
            'term' => $this->term,
            'service_charge' => $this->service_charge
        ];
    }
}
