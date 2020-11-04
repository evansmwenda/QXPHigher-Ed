@extends('layouts.app')

@section('content')
  <div class="row">
      @include('students.header')
  </div>
<div class="row">
    <div class="row exam-top">
        <div class="live-overlay">
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
            <h2>Live Classes</h2>
            <p>Education is your power. So learn more and more</p>
            <div class="col-md-3 live-button">
                <button data-toggle="modal" data-target="#modalCreateOptions"><i class="fa fa-plus"></i> Create</button>
            </div>
        </div>
      </div>
</div>
{{-- end of main row --}}
<div class="col-md-5 join">
   <h3>Join Class</h3>
   <form method="post" action="/admin/live-classes/join">{{ csrf_field() }}
      <div class="col-xs-12 form-group">
          <input type="text" name="meetingID" placeholder="Enter Class ID to Join" class="form-control" required>
          {{-- <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required> --}}
      </div>

      <div class="col-xs-12 form-group">
          <button type="submit">Join Live Class</button>
      </div>
  </form>
   
   
</div>

<div class="modal fade" id="modalCreateOptions" role="dialog">
  <div class="modal-dialog modal-sm">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
              <div class="row meeting">
              <h3>Create Meeting</h3>
              <div class="row col-md-6 instant">
                  <a href="/admin/live-classes/create"><button>Instant</button></a>
              </div>
              <div class="row col-md-5 later">
                  <a href="/admin/live-classes/schedule"><button>Scheduled</button></a>
              </div>
              </div>
        {{-- <a href="/admin/live-classes/create">
          <div class="text-center" style="display:flex;justify-content: center;align-items:center;height: 30px;">
            <h5>Start an Instant Meeting</h5>
          </div>
        </a>
        <hr>
        <a href="/admin/live-classes/schedule">
          <div class="text-center" style="display:flex;justify-content: center;align-items: center;height: 30px;">
            <h5>Schedule Meeting for Later</h5>
          </div>
        </a> --}}
        
      </div>
    </div>
    
  </div>
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