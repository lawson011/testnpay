<?php

namespace App\Models\Loans;

use Illuminate\Database\Eloquent\Model;

class CustomerLoanStatus extends Model
{
    public function loan(){
        return $this->hasMany(Loan::class);
    }
}
