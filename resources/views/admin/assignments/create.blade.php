@extends('layouts.app')

@section('content')
    <h3 class="page-title">Assignments</h3>    
    @if(Session::has("flash_message_error")) 
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div> 
          @endif 

    @if(Session::has("flash_message_success")) 
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_success') !!}</strong>
        </div> 
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">

            	<form method="post" enctype="multipart/form-data" action="/admin/assignments/create">{{ csrf_field() }}
            		<div class="col-xs-12 form-group">
	                	<div class="form-group">
	                        <label>Select Course</label>
	                        <select class="form-control" name="course_id" required>
	                        	<option value="0">Select Course</option>
	                        	@foreach($my_courses as $course)
	                        		<option value="{{ $course->course_id}}">{{ $course->course->title}}</option>
	                        	@endforeach
	                        </select>
	                    </div> 
	                </div>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Assignment Title</label>
                    	<input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required>
	                </div>
	                <div class="col-xs-12 form-group">
	                	<label for="exampleDescription">Description</label>
	                	<textarea name="description" class="form-control" id="exampleDescription" rows="3"></textarea>
	                </div>

	                <div class="col-xs-12 form-group">
	                	<div class="input-group">
	                      <div class="custom-file">
	                        <input type="file" name="assignment" class="custom-file-input" id="assignment" required>
	                      </div>
	                    </div>
	                </div>

	                
	                <div class="col-xs-12 form-group">
	                	<button type="submit" class="btn btn-primary"> Create Assignment</button>
	                </div>
	                

                </form>
    
            </div>
                <p>
			        <a href="{{ url('/admin/assignments') }}" class="btn btn-default">Back to list</a>
			    </p>
    
            
        </div>
    </div>

@endsection    

