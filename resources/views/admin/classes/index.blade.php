@extends('layouts.app')

@section('content')
    <h3 class="page-title">Live Classes</h3>
    <p>
        <a href="{{ url('/admin/events/create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    @if(Session::has("flash_message_error")) 
            <div class="alert progress-bar-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div> 
          @endif 

    @if(Session::has("flash_message_success")) 
        <div class="alert progress-bar-success alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_success') !!}</strong>
        </div> 
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($my_classes) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Class Title</th>
                        <th>Meeting ID</th>
                        <th>Course</th>
                        <th>Class Time</th>
                        <th>Action(s)</th>
                     
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($my_classes) > 0)
                    	@foreach($my_classes as $key=>$class)
                    		<tr data-entry-id="{{ $class->id }}">
	                            <td>{{ ++$key }}</td>
	                            <td>{{$class->title}}</td>
	                            <td>{{$class->meetingID}}</td>
	                            <td>Biology 101</td>
	                            <td>2020-10-12 10AM</td>
	                            <td><a href="{{ url('/admin/events/delete/'.$class->id)}}" class="btn btn-danger btn-sm">Delete</a></td>
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
        
        <!-- <div class="panel-body">
        	<div class="row .d-none .d-xs-block .d-sm-block .d-md-none">
	            <div class="col-sm-12 col-md-12 text-center" style="margin-bottom: 20px;">
	                <button onclick="toggleCreate()" class="btn btn-primary btn-lg" style="width:150px;color:white;text-align: center;"><span>Create Meeting</span></button>
	                <button onclick="toggleJoin()" class="btn btn-lg" style="width:150px;background-color:#3c8dbc;color:white;text-align: center;"><span>Join Meeting</span></button>


	            </div>
	        </div>

	        <div class="login-s" id="toggle-join" style="">
	        	<div class="container text-center">
	        		<div class="col-sm-8">
                		<form class="form-horizontal" 
                          role="form"
                          method="POST"
                          action="/admin/live-classes/join">
	                        <input type="hidden"
	                               name="_token"
	                               value="{{ csrf_token() }}">    

	                        <div class="form-group">
	                            <label class="col-md-4 control-label">Class ID</label>

	                            <div class="col-md-8">
	                                <input type="text"
	                                       class="form-control"
	                                       name="meetingID"
	                                       placeholder="Enter Class ID to join" 
	                                       required>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <div class="col-md-6 col-md-offset-5">
	                                <button type="submit"
	                                        class="btn"
	                                        style="margin-right: 15px;background-color: #3c8dbc;color:#fff;">
	                                    JOIN LIVE CLASS
	                                </button>
	                            </div>
	                        </div>
	                    </form>
              </div>
	        	</div>
              
          </div>

          <div class="login-s" id="toggle-create" style="display:none">
              <div class="container text-center">
                  <div class="col-sm-8">
                		<form class="form-horizontal" 
                          role="form"
                          method="POST"
                          action="/admin/live-classes/create">
	                        <input type="hidden"
	                               name="_token"
	                               value="{{ csrf_token() }}">

	                        <div class="col-xs-12 form-group">
			                	<div class="form-group">
			                        <label class="col-md-4 control-label">Select Course</label>
			                        <div class="col-md-8">
			                        	<select class="form-control" name="course_id" required>
				                        	<option>Select Course</option>
				                        	@foreach($my_courses as $course)
				                        		<option value="{{ $course->course_id}}">{{ $course->course->title}}</option>
				                        	@endforeach
				                        </select>
			                        </div>
			                        
			                    </div> 
			                </div>       

	                        <div class="form-group">
	                            <label class="col-md-4 control-label">Title</label>

	                            <div class="col-md-8">
	                                <input type="text"
	                                       class="form-control"
	                                       name="title"
	                                       placeholder="Enter title" 
	                                       required>
	                            </div>
	                        </div>

	                        <div class="col-xs-12 form-group">
	                        	<label class="col-md-4 control-label" for="mydate">Start/End Time</label>
		                        <div class="col-md-8">
		                        	<input type="text" id="mydate" class="daterange" name="event_start_end" style="width:100%;padding: 6px" required />
		                        </div>
		                        
		                    </div>


	                        <div class="form-group">
	                            <div class="col-md-6 col-md-offset-5">
	                                <button type="submit"
	                                        class="btn btn-primary"
	                                        style="margin-right: 15px;">
	                                    CREATE LIVE CLASS
	                                </button>
	                            </div>
	                        </div>
	                    </form>
              </div>
              </div>
          </div>
        </div> -->
    </div>
    
@endsection 
@section('javascript')
    @parent
<script type="text/javascript">
    function toggleJoin() {
        var x = document.getElementById("toggle-join");
        var y = document.getElementById("toggle-create");

        //check if create element is showing->if yes hide it
        if (x.style.display === "none") {
                x.style.display = "block";
                y.style.display = "none";
            }
    }
    function toggleCreate() {
        var x = document.getElementById("toggle-join");
        var y = document.getElementById("toggle-create");

        if (y.style.display === "none") {
                y.style.display = "block";
                x.style.display = "none";
            } 
    }

</script>
<script>
$(function() {
  $('.daterange').daterangepicker({
  	opens: 'auto',
  	// singleDatePicker:true,
  	drops:'auto',
  	opens:'center',
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'Y/M/DD HH:mm:ss'
    }
  });
});
</script>
@stop