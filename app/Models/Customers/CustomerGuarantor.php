<?php

namespace App\Customers\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CustomerGuarantor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'name', 'phone', 'address'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
