<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillerCategory extends Model
{
    protected $table = 'billers_categories';

    protected $fillable = [
        'identifier'
    ];

    public function billers()
    {
        return $this->hasMany(Biller::class,'billers_category_id');
    }
}
