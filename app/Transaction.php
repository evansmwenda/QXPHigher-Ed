<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = 'transactions';
    protected $fillable = [
        'id','user_id','phone','amount','currency','status','reference','is_used','description','tracking_id','payment_method','created_at','updated_at',
    ];
}
