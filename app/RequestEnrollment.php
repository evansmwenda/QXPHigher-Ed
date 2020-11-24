<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestEnrollment extends Model
{
    //
    public $timestamps = false;
    protected $table = 'request_enrollments';
    public $fillable = ['id','student_id','course_id','teacher_id','status','read'];  
    
}
