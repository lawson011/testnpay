<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CustomerRegistrationSetting extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
