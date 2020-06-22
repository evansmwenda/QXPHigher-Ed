<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmittedAssignments extends Model
{
    //
    public $timestamps = false;
    protected $table = 'submitted_assignments';
    public $fillable = ['id','assignment_id','user_id','filename','created_at','updated_at'];
}
