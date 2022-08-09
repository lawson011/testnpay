<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Transaction\TransactionInterface;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if( Cache::get('user'.auth()->user()->id.'identifier',0) === 0){
            $transactions       = app(TransactionInterface::class)->dailyTransactions(auth()->user()->id);
            $identifier_cache   = 'user'.auth()->user()->id.'identifier';

            Cache::put($identifier_cache,$transactions);
        }

        return [
            $this->mergeWhen(!empty($this->token) == true, [
                'token' => $this->token
            ]),
            $this->mergeWhen(!empty($this->refresh_token) == true, [
                'refresh_token' => $this->refresh_token
            ]),
            'identifier' => encrypt($this->id),
            'name' => $this->full_name,
            'email' => $this->email,
            'username' => $this->username,
            'referral_code' => $this->referral_code,
            'nuban' => $this->nuban,
            'phone' => $this->phone,
            'terms_and_condition' => $this->terms_and_condition,
            'is_active' => $this->is_active,
            'is_staff' => $this->is_staff,
            'is_agent' => $this->is_agent,
            'transaction_pin' => is_null($this->transaction_pin) ? false : true,
            'card_request' => new CardRequestResource($this->cardRequest->first()),
            'id_card' => new CustomerIdentityCardResource($this->identityCard->first()),
            'utility' => new CustomerUtilityResource($this->utility->first()),
            'next_of_kin' => new CustomerNextOfKinResource($this->nextOfKin->first()),
            'biodata' => new CustomerBioDataResource($this->bioData->first()),
            'guarantor' => $this->guarantor->first(),
            'cards' => CustomerCardResource::collection($this->card),
            'transactions' => TransactionHistoryResource::collection($this->transactions),
            'daily_transactions' => Cache::get('user'.auth()->user()->id.'identifier'),
        ];



    }
}
