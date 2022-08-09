<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;

class CustomerBlockStatus extends Model
{
    public function user(){
        return $this->belongsTo(Customer::class);
    }
}
