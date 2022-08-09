<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankOneBankResource extends JsonResource
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
            'cbn_code' => $this['Code'],
            'name'  => $this['Name'],
            'id' => $this['ID'],
            'abbr' => $this['Name'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
