<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CustomerActivityLog extends Model
{

    protected $fillable = [
        'ip','action','uri','body','message_type'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
