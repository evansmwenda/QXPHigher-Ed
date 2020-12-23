@extends('layouts.app')

@section('content')
  <div class="row">
    @include('students.header')
  </div>

    <div class="row" style="">
      <div class="row">
          <div class="col-md-8 course-top">
              <h3>Enrollments</h3>
          </div>
          <div class="col-md-4 course-top">
            <a href="{{ url('admin/enrollments/create') }}"><button><i class="fa fa-plus"></i> Add New</button></a>
          </div>
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
                        <tr>
                          <td>0</td>
                          <td>1</td>
                          <td>2</td>
                          <td>3</td>
                          <td>4</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
        </div>

</div>

@endsection    

