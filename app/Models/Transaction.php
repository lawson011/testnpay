<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'customer_id',
        'transaction_reference',
        'nip_transaction_reference',
        'amount',
        'sender_account_number',
        'receiver_account_number',
        'narration',
        'transaction_type',
        'device',
        'channel',
        'status'
        ];

    protected $casts = [
        'created_at'
    ];
}
