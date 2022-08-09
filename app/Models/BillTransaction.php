<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillTransaction extends Model
{
    protected $table = 'bills_transactions';

    protected $fillable = [
        'customer_id',
        'message',
        'billers_category_id',
        'reference',
        'etrazanct_reference',
        'account',
        'amount',
        'trx_reference',
        'status'
    ];
}
