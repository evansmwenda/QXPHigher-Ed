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
       <h3> Student Request Details</h3>
       <br>
       <i>Student Request Details</i>

       <div class="student-details">
        <h4>Geoffrey Mutua</h4>

       </div>
       <div class=" student-inner-details">
           <div class="row">
               <div class="col-md-6">
                   <h3 style=" font-size:20px">School Details</h3>
                    <h4>Institution </h4>
                    <span><strong>Nairobi University</strong></span>
                    <h4>Rquested Course </h4>
                    <span><strong>Biology 101</strong></span>
               </div>

                <div class="col-md-6">
                    <h3 style=" font-size:20px">Student Contacts</h3>
                    <h4>Student Email :</h4>
                    <i>Johndoe@gmail.com</i>
                    <h4>Student Number</h4>
                    <i>+125488854484848</i>
                    <hr>
                    <button style="background: #11BECC">Accept</button>
                    <button style="background: #dc3545">Reject</button>
                </div>
           </div>



       </div>
    </div>
    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>


@endsection