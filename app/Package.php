<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //
    public $timestamps = false;
    protected $table = 'packages';
    public $fillable = ['id','name','amount','created_at','updated_at'];
}
