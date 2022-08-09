<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillsResource extends JsonResource
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
            "category" => $this->billers_category_id,
            "name" => $this->identifier,
            "billers" => $this->billers,
            "slug" => $this->slug,
            "code" => $this->code,
            "operation" => $this->operation,
            "status" => $this->status,
            "verification" => $this->verification,
        ];
    }
}
