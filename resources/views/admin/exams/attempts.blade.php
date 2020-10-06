@extends('layouts.app')

@section('content')
    <h3 class="page-title">Exams</h3>
    <p>
        <a href="{{ url('/admin/exams') }}" class="btn btn-success">Back to Exams</a>
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
            {{ $test->title }} - Student Attempts
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
                            <td>{{ $key }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                  <a href="{{url('admin/exams/attempts/'.$id.'/'.$student->id)}}">Open File</a>
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

