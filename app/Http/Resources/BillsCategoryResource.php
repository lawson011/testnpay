<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillsCategoryResource extends JsonResource
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
            'name' => $this->identifier,
            'category' =>  BillsResource::collection($this->billers),
        ];
    }

    public function getMenu()
    {
        //get the menu for the
    }
}
