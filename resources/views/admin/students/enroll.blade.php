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
        <div class="row student-search">
            <form action="">
                <div class="form-group col-md-6">
                    <select name="" id="" class="form-control">
                        <option value="">Course Name 1</option>
                        <option value="">Course Name 1</option>
                        <option value="">Course Name 1</option>
                        <option value="">Course Name 1</option>
                    </select>
                </div>
                <div class="form-group col-md-8">
                    <label for="">Search Student</label>
                    <input type="text"  placeholder="Search Student" class="form-control">
                </div>
            <div class=" form-group col-md-4">
<br>
               <button>Enroll Student</button>
            </div>

            </form>
            
        </div>
       <h3>Enrolled Students</h3>
       
    <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Student Name</td>
                <td>Email Address</td>
                <td>Coourse Enrolled</td>
                <td>Options</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Biology</td>
                <td>256</td>
                <td></td>
                <td>
                    <button>Remove</button>
                </td>
            </tr>
          
        </tbody>
        </table>
    </div>

    <div class="col-md-4">
        @include('admin.recents')
    </div>
  </div>


@endsection