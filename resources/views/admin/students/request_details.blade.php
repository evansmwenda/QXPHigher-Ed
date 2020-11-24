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
   @foreach ($student_request as $item)
   <div class="row" style="background: #fff;">
    <div class="col-md-8 students">
       <h3> Student Request Details</h3>
       <a href="{{url('admin/students/requests')}}"><button class="btn btn-primary">Go Back</button></a>
       <br>
       <i>Student Request Details</i>

       <div class="student-details">
       <h4>{{$item->name}}</h4>

       </div>
       <div class=" student-inner-details">
           <div class="row">
               <div class="col-md-6">
                   <h3 style=" font-size:20px">School Details</h3>
                    <h4>N/A </h4>
                    <span><strong>Nairobi University</strong></span>
                    <h4>Rquested Course </h4>
                    <span><strong>{{$item->title}}</strong></span>
               </div>

                <div class="col-md-6">
                    <h3 style=" font-size:20px">Student Contacts</h3>
                    <h4>Student Email :</h4>
                    <i>{{$item->email}}</i>
                    <h4>Student Number</h4>
                    <i>{{$item->phone}}</i>
                    <hr>
                    @if($item->status =='Pending')
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ url('admin/students/accept') }}" method="post">
                                    <input type="hidden" value="{{$item->student_id}}" name="user_id">
                                    <input type="hidden" value="{{$item->id}}" name="enroll_id">
                                    <input type="hidden" value="{{$item->course_id}}" name="course_id">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button style="background: #11BECC;font-size:13px" type="submit">Accept</button>
                                </form>
                            </div>
                            <form action="{{ url('admin/students/reject') }}" method="post">
                                <input type="hidden" value="{{$item->student_id}}" name="user_id">
                                <input type="hidden" value="{{$item->id}}" name="enroll_id">
                                <input type="hidden" value="{{$item->course_id}}" name="course_id">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button style="background: #dc3545;font-size:13px" type="submit">Reject</button>
                                
                            </form>
                        </div>
                        @else
                        <h4>Request {{$item->status}}</h4>
                    @endif

                   
                 
                </div>
           </div>



       </div>
    </div>
    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>
   @endforeach



@endsection