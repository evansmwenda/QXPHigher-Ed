@extends('layouts.home')

@section('main')
  <div class="row">
    @include('students.header')
  </div>

  <div class="row" style="background: #fff;">
    <div class="col-md-8 students">
        <br>
        <div class="row student-search">
            <form action="{{ url('/courses/results') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group col-md-8">
                    <label for="">Search Course</label>
                    <input type="text" name="course"  placeholder="Enter Course Name" class="form-control">
                </div>
            <div class=" form-group col-md-4">
                <br>
               <button>Search</button>
            </div>

            </form>
            
        </div>    
        
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

       <h3>Searched Results</h3>
       
       
      <table class="table table-striped table-bordered table-stripped">
        <thead >
            <tr>
                <td>#</td>
                <td>Course Name</td>
                <td>Author/Teacher/Lecturer</td>
                <td>Institution</td>
                <td>Request Enrollement</td>

            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center">
                            <td colspan="5">No Results Found</td>
            </tr>        
        </tbody>
        </table>
    </div>
    <div class="col-md-4">
       @include('students.recentNotifications')
    </div>
  </div>
@endsection