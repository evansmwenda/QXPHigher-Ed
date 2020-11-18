@extends('layouts.home')

@section('main')
  <div class="row">
    @include('students.header')
  </div>

  <div class="row" style="background: #fff;">
    <div class="col-md-8 students">
      <h3>Message Details</h3>
      <br>
      <div class="student-details">
        
      @foreach ($details as $item)
      <h4>
          @if($item->status=='Pending')
          Request for Course Enrolment
          @endif
      </h4>
      </div>
      <div class=" student-inner-details">
          <div class="row">
                <p>You send a request to Joshua for the enrollment to Biology course</p>
              <p>Time {{$item->created_at}}</p>
              <p>Status: {{$item->status}}</p>

                   <hr>
                   @if($item->status=='Pending')
                   <button style="background: #dc3545; width:150px">Canel Request</button>
                   @else
                   @endif
              </div>
          </div>
          @endforeach
      </div>

    <div class="col-md-4">
        @include('students.recentNotifications')
    </div>
  </div>
@endsection