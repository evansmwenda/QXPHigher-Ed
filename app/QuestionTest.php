<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionTest extends Model
{
    //
    public $timestamps = false;
    protected $table = 'question_test';
    public $fillable = ['question_id','test_id']; 

    // public function course()
    // {
    //     return $this->belongsTo('App\Course');
    // }
    // public function submitted_assignments(){
    // 	return $this->belongsTo('App\SubmittedAssignments');
    // }
}
