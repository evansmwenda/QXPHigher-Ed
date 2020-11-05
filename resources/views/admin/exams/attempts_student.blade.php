@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>
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
            {{ $student_details->name }} - {{ $questions[0]->test->title }} 
        </div>
        
        <div class="panel-body">

            <div class="col-sm-10">
            <div class="panel-group" id="accordion">

                @if(count($question_options) >0)

                    @foreach($question_options as $key=>$question)
                    <div class="col-xs-12 form-group">
                        <p>{{ $key+1 }}. {{ $question->question }}</p>
                        @if(count($question->options) > 0)
                            <!-- has multiple choices-->
                            @foreach($question->options as $option)
                                <div class="col-xs-12 form-check">
                                    <input class="form-check-input" type="radio" name="answer{{$key}}[]" value="{{ $option->option_text}}">
                                    <label class="form-check-label">{{ $option->option_text}}</label>
                                </div>
                            @endforeach
                            <!-- add field to indicate the answer the student chose -->
                            <p style="color:green">Student answer: {{ $question_answers[$key]->answer }}</p> 
                        @else
                        <!-- open ended question -->
                            <div class="col-xs-9 form-group">
                                <label for="exampleDescription">Description</label>
                                <textarea name="answer{{$key}}" class="form-control" id="exam_description" rows="3" style="color: green;">Student answer: {{ $question_answers[$key]->answer }}</textarea>
                            </div>
                        @endif
                        

                    </div>
                    
                    @endforeach
                    <!-- add form to update the students exam results-->
                    <form role="form" method="post" action="{{('/admin/exams/grade/save')}}"> {{csrf_field() }}
                        <input type="hidden" name="u_id" value="{{ $student_details->id }}">
                        <input type="hidden" name="t_id" value="{{ $questions[0]->test->id }}">

                        <div class="col-xs-6 form-group">
                            <label for="exampleDescription">Award Marks</label>
                            <input type="text" name="marks" class="form-control" value="{{ $test_result }}" required>
                            
                        </div>

                        <div class="col-xs-12 form-group">
                            <input type="submit" value="Submit" class="btn btn-primary" />
                        </div>

                    </form>




                @else
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <p>No questions found.</p>
                    </h4>
                  </div>
                @endif
                <br/>
                <div class="col-xs-12">
                    <p>
                      <a href="{{ url('/admin/exams') }}" class="btn btn-default">Back to list</a>
                    </p>
                </div>
                
                
              </div>
            </div>
        </div>
    </div>

@endsection    

