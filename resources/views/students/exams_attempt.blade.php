@extends('layouts.home')

@section('main')
    <div class="row">
        @include('students.header')
        <div class="col-md-12" style="background: #fff">
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

          {{-- <div class="row exam-attempt">
           <h3>{{ $test_details->course->title }}</h3>
           <p>{{ $test_details->title }}</p>
          </div> --}}

          @if(count($exams) >0)
          <div class="row" style="margin-left: 60px">
            <div class="excard">Duration 2Hrs</div>
            <div class="excard">9:00AM - 12:00PM </div>
            <div class="excard">Time Left: 1Hr </div>
          </div>
          <form role="form" method="post" action="{{('/exams/save/'.$id)}}"> {{csrf_field() }}
            <input type="hidden" name="_count" value="{{$questions_count}}">
            <input type="hidden" name="test_id" value="{{$test_details->id}}">
          @foreach($exams as $key=>$exam)
          <div class="col-md-5 exams-tests">
              <div class="row" style="background: #D5D8DC;padding:8px; font-weight:900">
                {{ $key+1 }}. {{ $exam->question}}
                <input type="hidden" name="question{{$key}}" value="{{$exam->id}}">
              </div>
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
          <div class="col-xs-12 card-form-group">
            <input type="submit" value="Submit" class="btn btn-primary" />
          </div>
          </form>
          @else
            <div class="row text-center text-muted" style="font-size:25px; padding:20px"> <p>No questions found.</p></div>
          @endif
        </div>
    </div>
@endsection