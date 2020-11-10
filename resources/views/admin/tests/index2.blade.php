@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>
<div class="row">
    {{-- small left side div --}}
    <div class="col-md-4 exams-top" style="background: #fff">
        <h3>Quizzes</h3>
        <button data-toggle="modal" data-target="#modalCreateOptions"><i class="fa fa-plus"></i> Select Course</button>                        
        <hr>
        <h4>Course : {{$titles_array[0]->course->title or 'Select Course'}} </h4>
        <hr>
        {{-- <form action="/admin/exams" method="post">{{ csrf_field() }} --}}
            {{-- <input type="hidden" name="type" value="question"/> --}}
            @if(count($titles_array)>0)
                @foreach($titles_array as $exam)
                    <a href="{{url('/admin/tests/'.$exam->id)}}">
                        <label for="female">
                            <i class="fa fa-check"></i> 
                            <span type="submit">{{$exam->title}}</span>
                        </label>
                    </a>
                <br>
                @endforeach
                
            @else
                <p class="text-center">You have no exams created</p>
            @endif
            
        {{-- </form> --}}
    </div>
    {{-- display side --}}
    <div class="col-md-8 exam-questions">
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
        <h2>Questions</h2>
        <div class="exam-top-buttons">
            <a href="{{ url('/admin/tests/create/new') }}"><button style="background:#060646"><i class="fa fa-plus"></i>Create</button></a>
            @if($questions_array->isEmpty())
            {{-- //do nothing  $questions_array->isEmpty() ||  --}}
            @else 
                <a href="{{ url('/admin/tests/attempts/'.$questions_array[0]->test->id)}}"><button style="background: #FD6C03"><i class="fa fa-check"></i>Submited</button></a>
                <a href="{{ url('/admin/tests/delete/'.$questions_array[0]->test->id) }}"><button style="background: #71CA52"><i class="fa fa-trash"></i>Delete</button></a>
            @endif
        </div>
        
        <h3>{{$questions_array[0]->test->title or ''}}</h3>
        @if(count($my_questions) > 0)
            @foreach($my_questions as $key=>$question)
                <div class="disp-exams">
                    <p>{{++$key}}.{{$question->question }}</p>
                    {{-- <button style="background: #FD6C03">Edit</button> --}}
                    <a href="{{url('/admin/tests/delete-question/'.$question->id)}}" class="btn" style="background: #71CA52">
                        Remove
                    </a>
                </div>
            @endforeach
        @else
        <p class="text-center">No questions available</p>
        @endif
      
    </div>
</div>


        
        {{-- <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($my_tests) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Exam Title</th>
                        <th>Course</th>
                        <th>Published</th>
                        <th>Action(s)</th>
                     
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($my_tests) > 0)
                    	@foreach($my_tests as $test)
                    		<tr data-entry-id="{{ $test->id }}">
	                            <td>{{ $test->id }}</td>
	                            <td>{{ $test->title }}</td>
	                            <td>{{ $test->course->title}}</td>
	                            <td>{{ $test->published}}</td>
                            <td><a href="{{ url('/admin/exams/attempts/'.$test->id)}}" class="btn btn-info btn-sm">Answers</a> | <a id="{{$test->id}}" href="{{ url('/admin/exams/delete/'.$test->id)}}" class="btn btn-danger btn-sm">Delete</a></td>
	                        </tr>
                    	@endforeach
                        
                    @else
                        <tr>
                            <td colspan="10">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div> --}}
    </div>
    <div class="modal fade" id="modalCreateOptions" role="dialog">
        <div class="modal-dialog modal-sm">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body">
                <div class="row select-course">
                    <form method="post" action="/admin/tests">{{ csrf_field() }}
                        <input type="hidden" name="type" value="title"/>
                        <div class="col-xs-12 form-group">
                            <div class="form-group">
                                <h3>Select Course</h3>
                                <select name="course_id" class="form-control" id="">
                                    <option value="0">Select Course</option>
                                    @foreach($my_courses as $course)
                                        <option value="{{$course->course->id}}">{{$course->course->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning">Submit</button>
                            </div> 
                        </div>
                    </form>    
                    
                
                    {{-- <a href="/admin/exams"><button>Submit</button></a> --}}
                </div>
                
            </div>
          </div>
          
        </div>
      </div>
@endsection    

