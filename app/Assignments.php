<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    //
    public $timestamps = false;
    protected $table = 'assignments';
    public $fillable = ['id','course_id','title','description','media','created_at','updated_at']; 

    public function course()
    {
        return $this->belongsTo('App\Course');
    }
    public function submitted_assignments(){
    	return $this->belongsTo('App\SubmittedAssignments');
    }
}
