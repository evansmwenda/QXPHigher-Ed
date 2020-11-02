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
        <h2>Hello student,</h2>
        <p>Your education is your power. So learn more and more</p>
        
    </div>
  </div>

{{-- main content --}}
    <div class="row col-md-12" style="background: #fff">
      <div class="row">
        <div class="col-md-9">
          <h3 style="color: #060646; font-weight:600">Running Quizzes</h3>
        </div>

      </div>
     
     
      <div class="row">
        
          <div class="exam-card" onclick="location.href='{{url('#';">
            <div class="exam-header">
              Course Name
            </div>
            <p>Topic</p>
              <span>Author:</span><br>
              <i>Dr, Harry Garza</i>
            
          </div>
      </div>
      <div class="row">
        {{-- {{ $exams->links() }} --}}
       </div>
      
        <div class="row">
          <h3 class="text-center"> No exam Scheduled</h3>
        </div>
      
      <div class="exam-warning">
        <div class="row">
          <div class="col-md-2 exam-fa text-center">
            <span class="fa fa-user fa-2x"></span>
          </div>
          <div class="col-md-9 exam2">
            <h3>Take Advance Quiz to Achieve a Class Goal Set!</h3>
            <i>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea magnam repellendus optio magni commodi possimus.</i>
          </div>
        </div>
    </div>
    {{-- Submitted exams --}}
    <h3 style="color: #060646; font-weight:600">Completed Quizes</h3>
     

    <div class="row">
    
        <div class="exam-card">
          <div class="exam-header">
            Course Name
          </div>
          <p>Lesson Topic</p>
            <span>Author:</span><br>
           {{-- <i>{{ $ex am->owner_name }}</i> --}}
            <h4 class="pull-right" style="font-size: 30px; color:#060646">80%</h4>
        </div>
     

    </div>
    <div class="row">
      {{-- {{ $my_submitted_exams->links() }} --}}
     </div>
    {{-- @else
      <div class="row">
        <h3 class="text-center"> No exam found</h3>
      </div>
    @endif --}}
</div>
@endsection