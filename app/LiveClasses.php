<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveClasses extends Model
{
    //
    public $timestamps = false;
    protected $table = 'live_classes';
    public $fillable = ['id','title','meetingID','attendeePW','moderatorPW','owner'];  
}
