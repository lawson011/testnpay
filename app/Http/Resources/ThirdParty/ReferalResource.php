<?php

namespace App\Http\Resources\ThirdParty;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $referral_status = null;

        if ($this->referred_check) {
            $referral_status = $this->referred($this->referral_code)->map(function ($item) {
                $status = getUserAccountDetails(['account_number' => $item->nuban])['AvailableBalance'] / 100 >= 1000;
                $output = [];
                if ($status) {
                    $output['status'] = 'active';
                    $output['referral_code'] = $item['referral_code'];
                    $output['date'] = $item->created_at;
                } else {
                    $output['status'] = 'in-active';
                    $output['referral_code'] = $item['referral_code'];
                    $output['date'] = $item->created_at;
                }
                return $output;
            });
        }

        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'nuban' => $this->nuban,
            'referral_code' => $this->referral_code,
            'referred_by' => $this->referred_by,
            'date' => $this->created_at,
            $this->mergeWhen($this->referred_check == true, [
                'referred' => $referral_status,
            ]),

        ];
    }

}
