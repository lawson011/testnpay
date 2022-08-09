<?php

namespace App\Models;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomerOnboardingCustomer extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
