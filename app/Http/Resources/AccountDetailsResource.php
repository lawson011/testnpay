<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountDetailsResource extends JsonResource
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
            'Name'      => $this['Name'],
            'FirstName' => $this['FirstName'],
            'LastName'  => $this['LastName'],
            'Email'     => $this['Email'],
            'PhoneNo'  => $this['PhoneNo'],
            'Nuban'   => $this['Nuban'],
            'Number'   => $this['Number'],
            'ProductCode'   => $this['ProductCode'],
            'BVN'   => $this['BVN'],
            'AvailableBalance'   => $this['AvailableBalance']/100,
            'LedgerBalance'   => $this['LedgerBalance']/100,
            'Status'   => $this['Status'],
            'Tier'   => $this['Tier'],
            'MaximumBalance'   => $this['MaximumBalance'],
            'MaximumDeposit'   => $this['MaximumDeposit'],
            'IsSuccessful'   => $this['IsSuccessful'],
            'ResponseMessage'   => $this['ResponseMessage'],
            'PNDStatus'   => $this['PNDStatus'],
            'LienStatus'   => $this['LienStatus'],
            'FreezeStatus'   => $this['FreezeStatus'],
            'RequestStatus'   => $this['RequestStatus'],
            'ResponseDescription'   => $this['ResponseDescription'],
            'ResponseStatus'   => $this['ResponseStatus'],
        ];
    }
}
