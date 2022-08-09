<?php

namespace App\Models\Loans;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LoanServiceCharge extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
