<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    //
    public $timestamps = false;
    protected $table = 'assignments';
    public $fillable = ['id','course_id','title','description','media','submitted','created_at','updated_at']; 
}
