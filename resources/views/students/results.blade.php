@extends('layouts.home')

@section('main')
  <div class="row">
    @include('students.header')
  </div>

  <div class="row" style="background: #fff;">
    <div class="col-md-8 students">
        <br>
        <div class="row student-search">
            <form action="{{ url('/student_search_course') }}" method="post">
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
            @foreach ($results as $result)
              {{-- @if(count($results >0)) --}}
                <tr>
                    <td>1</td>
                   <td>{{$result->title}}</td>
                    <td>*</td>
                    <td>*</td>
                    <td>
                        <form action="{{ url('/student_sendrequest') }}" method="post">
                        <input type="hidden" value="{{$result->id}}" name="course">
                        <input type="hidden" value="{{$result->title}}" name="title">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <button style="background: #079DFF;font-size:13px" type="submit">Send Request</button>
                        </form>
                    
                    </td>
                </tr>
                {{-- @else
                <tr style="text-align: center">
                    <td colspan="5">No Results Found matching your search</td>
                    </tr> 
                @endif --}}

            @endforeach          
        </tbody>
        </table>
    </div>
    <div class="col-md-4">
       
    </div>
  </div>
@endsection