@extends('layouts.app')

@section('content')
    <h3 class="page-title">Live Classes</h3>
    <p>
    	<!-- <a href="{{ url('/admin/events/create') }}" class="btn btn-success">@lang('global.app_add_new')</a> -->
        <a href="#" data-toggle="modal" data-target="#modalCreateOptions" class="btn btn-success">@lang('global.app_add_new')</a>  | <a href="/admin/live-classes/join" d class="btn btn-success">Join Meeting</a>
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
	                            <td>{{$class->course->title}}</td>
	                            <td>{{$class->classTime}}</td>
	                            <td>
	                            	<a href="{{ url('/admin/live-classes/delete/'.$class->id)}}" class="btn btn-danger btn-sm">Delete</a> | 
	                            	<a href="{{ url('/admin/live-classes/start/'.$class->meetingID)}}" class="btn btn-info btn-sm">Start</a>
	                            </td>
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
<div class="modal fade" id="modalCreateOptions" role="dialog">
		    <div class="modal-dialog modal-sm">
		    
		      <!-- Modal content-->
		      <div class="modal-content">
		        <div class="modal-body">
		        	<a href="/admin/live-classes/create">
			        	<div class="text-center" style="display:flex;justify-content: center;align-items:center;height: 30px;">
			        		<h5>Start an Instant Meeting</h5>
			        	</div>
		        	</a>
		        	<hr>
		        	<a href="/admin/live-classes/schedule">
			        	<div style="display:flex;justify-content: center;align-items: center;height: 30px;">
			        		<h5>Schedule Meeting for Later</h5>
			        	</div>
		        	</a>
		        	
		        </div>
		      </div>
		      
		    </div>
		  </div>
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