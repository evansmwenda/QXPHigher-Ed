<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonStudent extends Model
{
    //
    public $timestamps = false;
    protected $table = 'lesson_student';
    public $fillable = ['lesson_id','user_id','created_at','updated_at'];  

 

}

