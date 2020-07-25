@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-10">
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
                <div class="panel-heading">{{ $test_details->title }} - {{ $test_details->course->title }}</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                    <div class="panel-group" id="accordion">

                        @if(count($exams) >0)
                          <form role="form" method="post" action="{{('/exams/save/'.$id)}}"> {{csrf_field() }}

                           @foreach($exams as $key=>$exam)
                            <div class="col-xs-12 form-group">
                              <p>{{ $key+1 }}. {{ $exam->question}}</p>
                              @if(count($exam->options) > 0)
                                <!-- has multiple choices-->
                                @foreach($exam->options as $option)
                                  <div class="col-xs-12 form-check">
                                    <input class="form-check-input" type="radio" name="answer{{$key}}[]" value="{{ $option->option_text}}">
                                    <label class="form-check-label">{{ $option->option_text}}</label>
                                  </div>
                                @endforeach  
                              @else
                                <!-- open ended question -->
                                <div class="col-xs-9 form-group">
                                  <label for="exampleDescription">Description</label>
                                  <textarea name="answer{{$key}}" class="form-control" id="exam_description" rows="3"></textarea>
                                </div>
                              @endif
                            </div>
                           @endforeach

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

                        <p>
                          <a href="{{ url('/exams') }}" class="btn btn-default">Back to list</a>
                        </p>

                        
                      </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection