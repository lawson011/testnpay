<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAccountsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // return parent::toArray($request);

        return [
            'account_status' => $this['AccountStatus'],
            'account_type' => $this['AccountType'],
            'account_balance' => $this['AccountBalance'],
            'account_number' => $this['AccountNumber'],
            'NUBAN' => $this['NUBAN'],
        ];
    }
}
