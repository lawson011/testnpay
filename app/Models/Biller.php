<?php

namespace App\Models;

use App\Models\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    protected $table = 'billers';

    protected $fillable = [
        'billers_category_id',
        'identifier',
        'billers',
        'slug',
        'code',
        'operation',
        'status',
        'verification'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new StatusScope());
    }

    public function category()
    {
        return $this->belongsTo(BillCategory::class);
    }
}
