<?php

namespace App\Models\Loans;

use App\Models\Customers\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function repaymentMethod(){
        return $this->belongsTo(RepaymentMethod::class);
    }

    public function status(){
        return $this->belongsTo(LoanStatus::class,'loan_status_id','id');
    }

    public function setting(){
        return $this->belongsTo(LoanSetting::class,'loan_setting_id','id');
    }
}
