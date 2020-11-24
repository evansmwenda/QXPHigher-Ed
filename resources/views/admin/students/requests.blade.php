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
       <h3> Students Requests</h3>
       <br>
       @if(Session::has("flash_message_error")) 
       <div class="alert enroll-error">
           <button type="button" class="close" data-dismiss="alert">x</button>
           {!! session('flash_message_error') !!}
           
       </div>
       @endif
       @if(Session::has("flash_message_success")) 
       <div class="alert enroll-success">
       <button type="button" class="close" data-dismiss="alert">x</button>
       {!! session('flash_message_success') !!}
       </div>
       @endif
       <div class="btn-enroll">
            <a href="{{url('admin/students')}}"><button style="padding:10px 15px;">Back to list</button></a>
        </div>
       <br/>
       <i>The following students have requested to join your courses as below</i>
       
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Student Name</td>
                <td>Email Address</td>
                <td>Course Requested</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody>
            @if(count($request_enrollments)>0)
                @foreach ($request_enrollments as $key => $request)
                    <tr
                    <?php
                        if($request->status =='Pending'){
                              ?>
                             style ="font-weight:900"
                              <?php
                        }else{
                              ?>
                               style ="font-weight:300"
                              <?php      
                        }
                        ?>
                       >
                        <td>{{++$key}}</td>
                        <td>{{$request->name}}</td>
                        <td>{{$request->email}}</td>
                        <td>{{$request->title}}</td>
                        <td>
                            <form action="{{ url('admin/students/requests/details',$request->id) }}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" value="{{$request->id}}" name="request_id">          
                                <button style="background: #079DFF;font-size:13px" type="submit">View Request</button>
                            </form>
                        {{-- <a href="{{url('admin/request_details')}}"><button>View</button></a>  --}}
                        </td>
                    </tr>
                @endforeach

            @else
            <tr>
                <td colspan="5" style="text-align: center">No requests found</td>
 
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