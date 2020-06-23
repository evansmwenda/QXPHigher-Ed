@extends('layouts.app')

@section('content')
    <h3 class="page-title">Events</h3>
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
            	<form method="post" action="/admin/events/create">{{ csrf_field() }}
            		<div class="col-xs-12 form-group">
	                	<div class="form-group">
	                        <label>Select Course</label>
	                        <select class="form-control" name="course_id" required>
	                        	<option>Select Course</option>
	                        	@foreach($my_courses as $course)
	                        		<option value="{{ $course->course_id}}">{{ $course->course->title}}</option>
	                        	@endforeach
	                        </select>
	                    </div> 
	                </div>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Event Title</label>
                    	<input type="text" name="event_title" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required>
	                </div>

	                <div class="col-xs-12 form-group">
	                	<label for="exampleInputEmail1">Start/End Time</label>
	                	<input type="text" id="endDate"  name="event_start_end" style="padding:6px;width: 100%"required/>
        
	                </div>
	                <div class="col-xs-12 form-group">
	                	<button type="submit" class="btn btn-primary"> Create Event</button>
	                </div>
	                

                </form>
            </div>
            
        </div>
    </div>

@endsection    

