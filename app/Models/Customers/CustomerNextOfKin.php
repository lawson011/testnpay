<?php

namespace App\Models\Customers;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class CustomerNextOfKin extends Model
{
    public function state(){
        return $this->belongsTo(State::class);
    }
}
