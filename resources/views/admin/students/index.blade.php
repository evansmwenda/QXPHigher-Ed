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
        <div class="pull-left">
            <h3>Enrolled Students</h3>
        </div>
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

    <div class="pull-right btn-enroll">
    <a href="{{url('admin/students/enroll')}}"><button >Enroll New Students</button></a>
    </div>
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                {{-- <td>#</td> --}}
                <td>Course Name</td>
                <td>No. Students Enrolled</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody>
            @if(!$my_courses->isEmpty())
                @foreach ($my_courses as $key=>$course)
                    <tr>
                        {{-- <td>{{$key++}}</td> --}}
                        <td>{{$course->course->title}}</td>
                        <td>{{$count_arr[$key]}}</td>
                        <td>
                        <a href="{{url('admin/students/list/'.$course->course->id)}}"><button>View</button></a>
                        </td>
                    </tr>
                @endforeach
            
            @else
                <tr class="text-center">
                    <td colspan="3">
                        You have no published courses
                    </td>
                </tr> 
            @endif
        </tbody>
        </table>
        <div class=" requests">
            <h3>Requests</h3>
            <div class="lecturer" style="background: #060646">
                <h2>Requests from students for enrollment</h2>
            </div>
            <table class="table table-bordered table-stripped">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Student Name</td>
                        <td>Email</td>
                        <td>Requested Course</td>
                        <td>State</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($request as $key=>$item)
                    <tr>
                    <td>{{++$key}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->email}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->status}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>


@endsection