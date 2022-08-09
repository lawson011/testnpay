<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;

class CardRequest extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
