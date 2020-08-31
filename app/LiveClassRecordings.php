<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveClassRecordings extends Model
{
    //
    public $timestamps = false;
    protected $table = 'live_class_recordings';
    public $fillable = ['id','meetingID','users'];  
}
