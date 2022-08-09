<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllAccountsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ledgerBalance = ($this['AccountType'] === 'SavingsOrCurrent') ? getSpecificAccountInformation($this['AccountNumber'],'LedgerBalance'
        ) : null;
        
        $tier = ($this['AccountType'] === 'SavingsOrCurrent') ? getSpecificAccountInformation(
            $this['AccountNumber'],'Tier'
        ) : null;

        $availableBalance = ($this['AccountType'] === 'SavingsOrCurrent') ? getSpecificAccountInformation(
            $this['AccountNumber'],'AvailableBalance'
        ) : null;

        return [
            'account-number'        => $this['AccountNumber'],
            'account-status'        => $this['AccountStatus'],
            'account-type'          => $this['AccountType'],
            'account-balance'       => $availableBalance/100,
            'customer-id'           => $this['CustomerID'],
            'customer-name'         => $this['CustomerName'],
            'customer-nuban'        => $this['NUBAN'],
            'ledger-balance'        => $ledgerBalance/100,
            'tier'                  => $tier,
        ];
    }
}
