<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveClasses extends Model
{
    //
    public $timestamps = false;
    protected $table = 'live_classes';
    public $fillable = ['id','title','classTime','meetingID','course_id','attendeePW','moderatorPW','owner'];  


    public function events(){
        return $this->belongsTo('App\Events');
    }
    public function course(){
        return $this->belongsTo('App\Course');
    }
}
