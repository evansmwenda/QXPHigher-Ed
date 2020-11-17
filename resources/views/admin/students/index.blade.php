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
    <div class="pull-right btn-enroll">
    <a href="{{url('admin\enroll')}}"><button >Enroll New Students</button></a>
    </div>
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Course Name</td>
                <td>No. Students Enrolled</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td>
                    <button>View</button>
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td>
                    <button>View</button>
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td>
                    <button>View</button>
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td>
                    <button>View</button>
                </td>
            </tr>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td>
                    <button>View</button>
                </td>
            </tr>
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Cena</td>
                        <td>Johncena@gmail.com</td>
                        <td>Biology</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>


@endsection