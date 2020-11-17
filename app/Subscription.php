<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    public $timestamps = false;
    protected $table = 'subscriptions';
    public $fillable = ['id','user_id','package_id','expiry_on'];

    public function package(){
        return $this->belongsTo('App\Package');
    }
}
