<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    //
    public $timestamps = false;
    protected $table = 'course_user';
    public $fillable = ['course_id','user_id'];  

    public function course(){
    	return $this->belongsTo('App\Course');
    }
}
