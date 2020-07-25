<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSubmits extends Model
{
    //
    public $timestamps = false;
    protected $table = 'exam_submits';
    public $fillable = ['id','test_id','user_id','created_at','updated_at'];

    public function exam(){
    	return $this->belongsTo('App\Test');
    }
}
