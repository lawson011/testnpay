<?php

namespace App\Models\FixedAccount;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FixedAccountSetting extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
