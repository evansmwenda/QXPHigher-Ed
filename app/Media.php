<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    public $timestamps = false;
    protected $table = 'media';
    public $fillable = ['id','model_id','model_type','collection_name','name','file_name','disk','size','manipulations','custom_properties','order_column','updated_at','created_at']; 

    public function course(){
    	return $this->belongsTo('App\Course');
    }

    public function liveclasses(){
    	return $this->hasMany('App\LiveClasses');
    }
}
