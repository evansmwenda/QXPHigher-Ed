@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>
<div class="row">
    {{-- small left side div --}}
    <div class="col-md-4 exams-top" style="background: #fff">
        <h3>Created Exams</h3>
        <button data-toggle="modal" data-target="#modalCreateOptions"><i class="fa fa-plus"></i> Select Course</button>                        
        <hr>
        <h4>Course : {{$titles_array[0]->course->title or 'Select Course'}} </h4>
        <hr>
        {{-- <form action="/admin/exams" method="post">{{ csrf_field() }} --}}
            {{-- <input type="hidden" name="type" value="question"/> --}}
            @if(count($titles_array)>0)
                @foreach($titles_array as $exam)
                    <a href="{{url('/admin/exams/'.$exam->id)}}">
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
            <a href="{{ url('/admin/exams/create/new') }}"><button style="background:#060646"><i class="fa fa-plus"></i>Create</button></a>
            @if(empty($questions_array))
            {{-- //do nothing  $questions_array->isEmpty() ||  --}}
            @else                      
                <a href="{{ url('/admin/exams/attempts/'.$questions_array[0]->test->id)}}"><button style="background: #FD6C03"><i class="fa fa-check"></i>Submited</button></a>
                <a href="{{ url('/admin/exams/delete/'.$questions_array[0]->test->id) }}"><button style="background: #71CA52"><i class="fa fa-trash"></i>Delete</button></a>
            
            @endif
        </div>
        
        <h3>{{$questions_array[0]->test->title or ''}}</h3>
        @if(count($my_questions) > 0)
            @foreach($my_questions as $key=>$question)
                <div class="disp-exams">
                    <p>{{++$key}}.{{$question->question }}</p>
                    {{-- <button style="background: #FD6C03">Edit</button> --}}
                    <a href="{{url('/admin/exams/delete-question/'.$question->id)}}" class="btn" style="background: #71CA52">
                        Remove
                    </a>
                </div>
            @endforeach
        @else
        <p class="text-center">No questions available</p>
        @endif
      
    </div>
</div>
    </div>
    <div class="modal fade" id="modalCreateOptions" role="dialog">
        <div class="modal-dialog modal-sm">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body">
                <div class="row select-course">
                    <form method="post" action="/admin/exams">{{ csrf_field() }}
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
                </div>
            </div>
          </div>
          
        </div>
      </div>
@endsection    

