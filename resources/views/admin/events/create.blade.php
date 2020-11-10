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
                        <div class="form-group">
                            <label>Event Type</label>
                            <select class="form-control" name="type" required>
                                <option value="">Select Event</option>
                                <option value="class">Live Class</option>
                                <option value="exam">Examination</option>
                                <option value="assignment">Assignment</option>
                                <option value="other">Other</option>
                            </select>
                        </div> 
                    </div>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Event Title</label>
                    	<input type="text" name="event_title" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required>
	                </div>

	                <!-- <div class="col-xs-12 form-group">
                            <label for="endDate">Start/End Time</label>
                        <input type="text" id="endDate"   />
	                </div> -->

                    <div class="col-xs-12 form-group">
                        <label for="mydate">Start/End Time</label>
                        <input type="text" id="mydate" class="daterange" name="event_start_end" style="width: 100%;padding: 6px" required />
                    </div>

                    <div class="col-xs-12 form-group">
                        <label for="favcolor">Select color:</label>
                        <input type="color" id="favcolor" name="favcolor" value="#00c0ef" >
                    </div>

	                <div class="col-xs-12 form-group">
	                	<button type="submit" class="btn btn-primary"> Create Event</button>
	                </div>
	                

                </form>
            </div>
            <p>
                <a href="{{ url('/admin/events') }}" class="btn btn-default">Back to list</a>
            </p>
            
        </div>
    </div>

@endsection
@section('javascript')
    @parent
<!-- <script>
        var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
        $('#startDate').datepicker({
            timePicker: true,
            uiLibrary: 'bootstrap4',
            iconsLibrary: 'fontawesome',
            minDate: today,
            maxDate: function () {
                return $('#endDate').val();
            }
        });
        $('#endDate').daterangepicker({
      timePicker: true,
      timePickerIncrement: 5,
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    });
    </script> -->
<script>
$(function() {
  $('.daterange').daterangepicker({
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

