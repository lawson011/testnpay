<?php

namespace App\Models\Customers;

use App\Models\UtilityType;
use Illuminate\Database\Eloquent\Model;

class CustomerUtility extends Model
{
    public function utilityType(){
        return $this->belongsTo(UtilityType::class);
    }
}
