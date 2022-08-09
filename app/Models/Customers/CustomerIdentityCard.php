<?php

namespace App\Models\Customers;

use App\Models\IdentityCardType;
use Illuminate\Database\Eloquent\Model;

class CustomerIdentityCard extends Model
{
    public function identityCardType(){
        return $this->belongsTo(IdentityCardType::class);
    }
}
