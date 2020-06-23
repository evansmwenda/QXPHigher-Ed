@extends('layouts.app')

@section('content')
    <h3 class="page-title">Calendar Events</h3>
    <p>
        <a href="{{ url('/admin/events/create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>
    
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
        
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($my_events) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Event Title</th>
                        <th>Course</th>
                        <th>Event Time</th>
                        <th>Action(s)</th>
                     
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($my_events) > 0)
                    	@foreach($my_events as $event)
                    		<tr data-entry-id="{{ $event->id }}">
	                            <td>{{ $event->id }}</td>
	                            <td>{{ $event->title }}</td>
	                            <td>{{ $event->course->title}}</td>
	                            <td>{{ $event->event_start_time}}</td>
	                            <td><a href="#" class="btn btn-primary btn-sm">Edit</a> | <a href="#" class="btn btn-danger btn-sm">Delete</a></td>
	                        </tr>
                    	@endforeach
                        
                    @else
                        <tr>
                            <td colspan="10">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection    

