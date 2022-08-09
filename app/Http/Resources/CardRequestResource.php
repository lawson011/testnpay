<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardRequestResource extends JsonResource
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
            'status' => cardRequestStatusById($this->card_request_status_id)->name,
            'pickup_type' => $this->pickup_type,
            'user_remarks' => $this->customer_remarks,
            'admin_remarks' => $this->user_remarks
        ];
    }
}
