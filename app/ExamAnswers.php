<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamAnswers extends Model
{
    //
    public $timestamps = false;
    protected $table = 'exam_answers';
    public $fillable = ['id','test_id','question_id','answer','user_id','created_at','updated_at'];

    public function exam(){
    	return $this->belongsTo('App\Test');
    }
}
