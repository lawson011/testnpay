<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'platform', 'ip','action','url','body','message_type',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
