<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCardResource extends JsonResource
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
            'exp_month' => $this->exp_month,
            'exp_year' => $this->exp_year,
            'card_type' => $this->card_type,
            'bank' => $this->bank,
            'default' => $this->default
        ];
    }
}
