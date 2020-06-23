@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.tests.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
            	<form method="post" action="/admin/events/create">
            		<div class="col-xs-12 form-group">
	                	<div class="form-group">
	                        <label>Select Course</label>
	                        <select class="form-control">
	                          <option>Select Course</option>
	                          <option>option 2</option>
	                          <option>option 3</option>
	                          <option>option 4</option>
	                          <option>option 5</option>
	                        </select>
	                    </div> 
	                </div>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Email address</label>
                    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
	                </div>

	                <div class="col-xs-12 form-group">
	                	<label for="exampleInputEmail1">Start/End Time</label>
	                	<input id="endDate" />
        
	                </div>
	                <button type="submit" class="btn btn-primary"> Create Event</button>

                </form>
            </div>
            
        </div>
    </div>

@endsection    

