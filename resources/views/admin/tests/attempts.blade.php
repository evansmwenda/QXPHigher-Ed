@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>
    <h3 class="page-title">Quizzes</h3>
    <p>
        <a href="{{ url('/admin/tests') }}" class="btn btn-primary">Back to Quizzes</a>
    </p>
    
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

    <div class="panel panel-default">
        <div class="panel-heading">
            Quizzes - Student Attempts
        </div>
        
        <div class="panel-body">
          @if(count($students) > 0 )

            <table class="table table-bordered table-striped {{ count($students) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Student Name</th>
                        <th>File</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($students as $key=>$student)
                        <tr data-entry-id="{{ $key }}">
                            <td>{{ ++$key }}</td>
                            <td>{{ $student->user_name }}</td>
                            <td>
                                  <a href="{{url('admin/tests/attempts/'.$student->test_id.'/'.$student->user_id)}}">Open File</a>
                            </td>
                         </tr>
                    @endforeach
                </tbody>
            </table>
          @else
            <p>No student has attempted exam</p>  
          @endif  
          
      </div>
    </div>

@endsection    

