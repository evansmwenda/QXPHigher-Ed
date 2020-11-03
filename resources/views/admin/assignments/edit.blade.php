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

			<form method="post" enctype="multipart/form-data" action="/admin/assignments/update/{{$assignment->id}}">{{ csrf_field() }}
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
					<input type="text" name="title" value="{{$assignment->title}}" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required>
	                </div>
	                <div class="col-xs-12 form-group">
	                	<label for="exampleDescription">Description</label>
	                	<textarea name="description" value="{{$assignment->description}}" class="form-control" id="exampleDescription" rows="3"></textarea>
	                </div>

	                <div class="col-xs-12 form-group">
	                	<div class="input-group">
	                      <div class="custom-file">
	                        <input type="file" name="assignment" value="{{$assignment->media}}" class="custom-file-input" id="assignment" required>
	                      </div>
	                    </div>
	                </div>

	                
	                <div class="col-xs-12 form-group">
	                	<button type="submit" class="btn btn-primary"> Update Assignment</button>
	                </div>
	                

                </form>
    
            </div>
                <p>
			        <a href="{{ url('/admin/assignments') }}" class="btn btn-default">Back to list</a>
			    </p>
    
            
		</div>
		<hr>
		@if(count($submitted_assignments_array) > 0 )
			<table class="table table-bordered table-striped {{ count($submitted_assignments_array) > 0 ? 'datatable' : '' }}">
				<thead>
					<tr>
						<th>id</th>
						<th>Student Name</th>
						<th>File</th>
					</tr>
				</thead>
				
				<tbody>
					@if (count($submitted_assignments_array) > 0)
						@foreach($submitted_assignments_array as $key=>$submitted_assignment)
							<tr data-entry-id="{{ $submitted_assignment->id }}">
								<td>{{ ++$key }}</td>
								<td>{{ $submitted_assignment->user->name }}</td>
								<td>
									<a href="{{url('uploads/assignments/'.$assignment->course->slug.'/'.$submitted_assignment->filename)}}" download>Download File</a></td>
							</tr>
						@endforeach
						
					@else
						<tr>
							<td colspan="10">No students have submitted</td>
						</tr>
					@endif
				</tbody>
			</table>
			@else
			<p>No student has submitted their assignment</p>  
			@endif
    </div>

@endsection    

