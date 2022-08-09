<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'narration' => $this['Narration'],
            'transaction_date' => Carbon::parse($this['TransactionDate'])->format('Y-m-d'),
            'transaction_date_string' => $this['TransactionDateString'],
            'reference' => $this['ReferenceID'],
            'amount' => $this['Amount'],
            'opening_balance' => $this['OpeningBalance'],
            'debit' => $this['Debit'],
            'credit' => $this['Credit']
        ];
    }
}
