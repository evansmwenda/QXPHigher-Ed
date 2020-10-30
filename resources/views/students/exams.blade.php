@extends('layouts.home')

@section('main')
<div class="row">
 @include('students.header')
</div>
{{-- content  --}}

<div class="Row">
  <div class="row exam-top">
    <div class="exam-overlay">
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
        <h2>Hello student</h2>
        <p>Your education is your power. So learn more and more</p>
        <span><strong>Good luck in your exams today</strong></span>
    </div>
  </div>

{{-- main content --}}
    <div class="row col-md-12" style="background: #fff">
      <div class="row">
        <div class="col-md-9">
          <h3 style="color: #060646; font-weight:600">Scheduled Exams</h3>
        </div>
        
        <div class="col-md-3 pull-right">
          <a href="/certification"><h3>My Certificates</h3></a>
          </div>

      </div>
     
      @if(count($exams) >0)
      <div class="row">
        @foreach($exams as $exam)
          <div class="exam-card" onclick="location.href='{{url('/exams/save/'.$exam->id)}}';">
            <div class="exam-header">
              {{ $exam->title}}
            </div>
            <p>{{ $exam->course->title}}</p>
              <span>Author:</span><br>
              <i>Dr, Harry Garza</i>
              <h4 class="pull-right">30th November,2020</h4>
          </div>
         @endforeach
      </div>
      <div class="row">
        {{ $exams->links() }}
       </div>
      @else
        <div class="row">
          <h3 class="text-center"> No exam Scheduled</h3>
        </div>
      @endif
      <div class="exam-warning">
        <div class="row">
          <div class="col-md-2 exam-fa text-center">
            <span class="fa fa-user fa-2x"></span>
          </div>
          <div class="col-md-9 exam2">
            <h3>Carefully read through the exam instructiosn prior to starting</h3>
            <i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea magnam repellendus optio magni commodi possimus.</i>
          </div>
        </div>
    </div>
    {{-- Submitted exams --}}
    <h3 style="color: #060646; font-weight:600">Submitted Exams</h3>
     
    @if(count($exams) >0)
    <div class="row">
      @foreach($exams as $exam)
        <div class="exam-card">
          <div class="exam-header">
            {{ $exam->title}}
          </div>
          <p>{{ $exam->course->title}}</p>
            <span>Author:</span><br>
            <i>Dr, Harry Garza</i>
            <h4 class="pull-right">30th November,2020</h4>
        </div>
       @endforeach

    </div>
    <div class="row">
      {{ $exams->links() }}
     </div>
    @else
      <div class="row">
        <h3 class="text-center"> No exam Scheduled</h3>
      </div>
    @endif
</div>
@endsection