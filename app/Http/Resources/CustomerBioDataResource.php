<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBioDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bvn' => $this->bvn,
            'dob' => $this->dob,
            'occupation' => $this->occupation,
            'salary_range' => $this->salary_range,
            'address' => $this->address,
            'city' => $this->city,
            'state' => ($this->state) ? $this->state->name : null,
            'photo_url' => $this->photo,
            'signature_url' => $this->signature,
            'upload_photo_count' => $this->upload_photo_count
        ];
    }
}
