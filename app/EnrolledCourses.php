<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrolledCourses extends Model
{
    //
    public $timestamps = false;
    protected $table = 'enrolled_courses';
    public $fillable = ['id','course_id','lesson_id','user_id','total_lessons','create_at','update_at'];  


}
