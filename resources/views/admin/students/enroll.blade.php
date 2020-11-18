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
        <div class="row" style="margin-left: 10px">
            <h3>Enroll New Students</h3>
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
        @if(count($my_courses) > 0)
            <div class="row student-search">
                <form method="POST" action="{{url('admin/students/enroll')}}">{{ csrf_field() }}
                    <div class="form-group col-md-6">
                        <select name="course_id" id="" class="form-control" required>
                            <option value="">Select Course</option>
                            @foreach ($my_courses as $course)
                            <option value="{{$course->course->id}}">
                                {{$course->course->title}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="">Search Student</label>
                        <input type="text" class="form-control typeahead" name="search" placeholder="Search Student" required autocomplete="off">
                    </div>
                    <div class=" form-group col-md-4"><br>
                    <button>Enroll Student</button>
                    </div>

                </form>
            </div>
        @endif
        
       <h3>Enrolled Students</h3>
       
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Student Name</td>
                <td>Email Address</td>
                <td>Course Enrolled</td>
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
                        <td>{{$enrollment->course_title}}</td>
                        <td>
                            <a href="{{url('admin/students/list/'.$enrollment->course_id.'/remove/'.$enrollment->id)}}" 
                                class="btn btn-primary">Remove</a>
                        </td>
                    </tr>
                @endforeach
 
            @else 
            <tr class="text-center">
                <td colspan="5">
                    You have no enrolled students in your course(s)
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
@section('javascript')
<script type="text/javascript">
    // console.log({{ url('/admin/autocomplete') }});
            var route = "{{ url('/admin/autocomplete') }}";
            $('input.typeahead').typeahead({
                source:function(terms,process){
                    return $.get(route,{terms:terms},function(data){
                        console.log(data[0]['id']);
                        return process(data);
                    });
                },
            });
    </script>    
@endsection