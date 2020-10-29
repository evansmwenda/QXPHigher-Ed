@extends('layouts.home')

@section('main')
<div class="row" >
    <div class="col-md-8">
        <div class="row top-header-2">
            <div class="col-md-12 col-sm-12" >
                <div class="col-sm-6">
                    <div class="form-group has-search">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </div>
                <div class="col-sm-2">
                    <span class="fa fa-shield-alt fa-2x"></span>
                </div>
                <div class="col-sm-2">
                    <span class="fa fa-bell fa-2x"></span>
                </div>
                <div class="col-sm-2">
                    <span class="fa fa-calendar-alt fa-2x"></span>
                </div>
            </div> 
        </div>
    </div>
    <div class="col-md-4 dashboard-right">
        <div class="row top-right">
            <i class="fa fa-user fa-2x"></i> 
                <a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="fa fa-bars"></span>
                </a> 
        </div>
    </div>
</div>
<div class="row" style="background: #fff">
{{-- Content --}}
<div class="row class-top">
    <div class="class-overlay">
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
        <h2>Join Live Class</h2>
        <form role="form" method="POST" action="/live-classes/join">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
          <div class="form-group">          
                <div class="col-md-4 col-sm-12">
                    <input type="text" class="form-control" name="meetingID" placeholder="Enter Class ID to join" required>
                </div>
          </div>
          <div class="form-group">
              <div class="col-md-2">
                <button type="submit" class="btn btn-primary"> Join
                </button>
              </div>
          </div>
          </div>
      </form>
    </div>
</div>
{{-- display the scheduled classses --}}
<div class="row scheduled-classes col-md-6">
    <div class="row">
    <h3>Upcoming Class Sessions</h3>
    <span>Scheduled class sessions and calls</span>
    </div>
    <hr>
    @if (count($my_classes)>0)
    @foreach ($my_classes as $item)
    <div class="row">
        <div class="scheduled-clock">
            <i class="fa fa-clock fa-2x"></i>
        </div>
        <div class="scheduled-time">
            <span>2:00PM</span>
            <i>3:00PM</i>
        </div>
        <div class="scheduled-title">
            <p>{{$item->title}}</p>
            <i>({{$item->course->title}})</i>
        </div>
    </div>
    @endforeach
    @else
      <div class="row text-center">
          <p class="text-muted">You dont have any scheduled classes</p>
      </div>
    @endif

</div>

{{-- right side content --}}
<div class="col-md-5">
   <div class="col-md-12 top-section">
       <h3>Previous Sessions</h3>

       <table class="table table-striped">        
            <thead>
                <tr>
                    <th>All</th>
                    <th>Time</th>
                </tr>                
            </thead>
            <tbody>
                <tr>
                    <td>Lecturer Name
                        <i>Biology 101</i>
                    </td>
                    <td>2:45 PM</td>
                </tr>
                <tr>
                    <td>Lecturer Name</td>
                    <td>2:45 PM</td>
                </tr>
                <tr>
                    <td>Lecturer Name</td>
                    <td>2:45 PM</td>
                </tr>
            </tbody>        
       </table>
   </div>
</div>
</div>

@endsection