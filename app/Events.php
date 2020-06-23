<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    //
    public $timestamps = false;
    protected $table = 'events';
    public $fillable = ['id','title','course_id','event_start_time','event_end_time','create_at','update_at']; 

    public function course(){
    	return $this->belongsTo('App\Course');
    }
}
