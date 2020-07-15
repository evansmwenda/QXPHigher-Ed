@extends('layouts.app')

@section('content')
    <h3 class="page-title">Exams</h3>
    <p>
        <a href="{{ url('/admin/exams/create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
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
            <table class="table table-bordered table-striped {{ count($my_tests) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Exam Title</th>
                        <th>Course</th>
                        <th>Published</th>
                        <th>Action(s)</th>
                     
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($my_tests) > 0)
                    	@foreach($my_tests as $test)
                    		<tr data-entry-id="{{ $test->id }}">
	                            <td>{{ $test->id }}</td>
	                            <td>{{ $test->title }}</td>
	                            <td>{{ $test->course->title}}</td>
	                            <td>{{ $test->published}}</td>
	                            <td><a href="{{ url('/admin/exams/delete/'.$test->id)}}" class="btn btn-danger btn-sm">Delete</a></td>
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

