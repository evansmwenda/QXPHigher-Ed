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

  <div class="row" style="background: #fff;">
    <div class="col-md-8 students">
       <h3>Course Students</h3>
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
       <br>
       <h3>{{$course->title}}</h3>
       <i>Students enrolled to Biology Course</i>
       
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Student Name</td>
                <td>Email Address</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody>
            @if(!$enrollments->isEmpty())
                @foreach ($enrollments as $key=>$enrollment)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$enrollment->user_name}}</td>
                        <td>{{$enrollment->user_email}}</td>
                        <td>
                            <a href="{{url('admin/students/list/'.$enrollment->course_id.'/remove/'.$enrollment->id)}}" 
                                class="btn btn-primary">Remove</a>
                        </td>
                    </tr>
                @endforeach
 
            @else 
            <tr class="text-center">
                <td colspan="5">
                    You have no enrolled students in your course
                </td>
            </tr>
               
            @endif
          
        </tbody>
        </table>
    </div>

    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>


@endsection