@extends('layouts.app')

@section('content')
<div class="col-md-8">
  <div class="row top-header-2-teacher">
      <div class="col-md-12 col-sm-12" >
          <div class="col-sm-6">
              <div class="form-group has-search">
                  <input type="text" class="form-control" placeholder="Search">
              </div>
          </div>
          <div class="col-md-6">
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
</div>
<div class="col-md-4 dashboard-right">
  <div class="row top-right-teacher">
  <a href=""><i class="fa fa-user"></i>  {{\Auth::user()->name}}</a> 
          <a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="fa fa-bars"></span>
          </a> 
  </div>
</div>


{{-- Main Body --}}
<div class="row" style="background: #fff">

  <div class="col-md-8">
		<div class="qxp-back">
        <div class="custom-search">
          <p>You have 3 days left for your subscription</p>
        </div>

        <button class="custom-search-button">Renew</button>

        <div class="row light-bg">
          <p>Show All</p>
          <button>All</button>
          <button>Confirmed</button>
        </div>
        <h3 style="color: #FD6C03">Overview</h3>
        <div class="row">
          <div class="col-md-6">
            <h3>Account Type : Teacher</h3>
            <h3>Institution : None</h3>
          </div>
          <div class="col-sm-5">
            <h3>Package : Premium</h3>
            <h3>Enrolled Students : 385</h3>
          </div>
        </div>
    </div>

      <div class="row teacher-courses-dashboard">
        <h3>My Registered Courses</h3>
        @foreach($courses as $course)
        <div class="col-sm-12 col-lg-3 col-md-3" style="margin-bottom: 20px;">
            <a href="{{url('admin/courses/'.$course->id.'/edit')}}"><div class="coarse-list"></div></a>
            <a href="{{url('admin/courses/'.$course->id.'/edit')}}"><p style="color:#060646;margin: 0px !important">{{$course->title}}</p></a>
            @for ($star = 1; $star <= 5; $star++)
            @if ($course->rating >= $star)
                <span class="glyphicon glyphicon-star" style="font-size: 10px;"></span>
            @else
                <span class="glyphicon glyphicon-star-empty" style="font-size: 10px;"></span>
            @endif
            @endfor

                <div class="row ">
                    <a href="{{url('admin/lessons?course_id='.$course->id)}}">
                        <button class="coarse-button" style="height:30px;">LESSONS <i class="fa fa-web"></i></button>
                    </a>
                    
                    <a href="{{url('admin/courses/'.$course->id.'/edit')}}">
                        <button class="coarse-button-2" style="height: 30px;">EDIT 
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </a>
                </div>
        </div>
        @endforeach

      </div>
      <div class="row">
        {{$courses->render()}}
      </div>

     <div class="row">Assignments</div>

  </div>
  <div class="col-md-4">
      @include('admin.recents')
      <div class="lecturer" style="background: #11BECC">
        <h2>Requests for student enrolment</h2>
    </div>
      <div class="admin-news" style="overflow-y:scroll">
        <table class="table tab-default table-bordered table-striped">
          <thead>
            <tr>
              <td>#</td>
              <td>Student Name</td>
              <td>Requested Course</td>
            </tr>

          </thead>
          <tbody>
             <a href=""><tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Biology</td>
              </tr>
             </a>

          </tbody>
        </table>
    </div>
  </div>
</div>
@endsection
