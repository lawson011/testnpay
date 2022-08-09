<?php

namespace App\Models\Customers;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class CustomerBioData extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }
}
