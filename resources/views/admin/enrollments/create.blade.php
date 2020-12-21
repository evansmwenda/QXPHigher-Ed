@extends('layouts.app')

@section('content')
    <h3 class="page-title">Enrollments</h3>    
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

            	<form method="post" enctype="multipart/form-data" action="/admin/enrollments/create">{{ csrf_field() }}
            		<div class="col-xs-12 form-group">
	                	<div class="form-group">
	                        <label>Select Role</label>
	                        <select class="form-control" name="role_id" required>
	                        	<option value="0">Select Role</option>
	                        	@foreach($roles as $role)
	                        		<option value="{{ $role->id}}">{{ $role->title}}</option>
	                        	@endforeach
	                        </select>
	                    </div> 
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

