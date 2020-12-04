@extends('layouts.home')

@section('main')
<div class="row">
	@include('students.header')
</div>
<div class="row" style="background: #fff; height:600px; overflow-y:scroll;overflow-x:hidden">
	<div class="col-md-8">
	
		@if(count($assignments) > 0)
		@foreach($assignments as $assignment)
		  <div class="assignment-card">
			<div class="col-sm-3 col-md-2 text-center" style="">
			  <img src="https://placehold.it/80" style="padding-top:3px;"/>
			</div>
			<div class="assignment-text col-sm-8 col-md-8" style="">
			  <p style="font-size: 1.3em;">{{ $assignment->title}}</p>
			  <p style="font-size: 1em;">Feb 4 2020 - March 4 2020</p>
			<p style="font-size: .8em;color:grey">{{ $assignment->course->title}}</p>
			</div>
			<div class="col-sm-1 col-md-2 text-center" style="">
			  <a href="{{ url('assignments/attempt/'.$assignment->id) }}" class="btn btn-info assignment-link" style="">Open</a>
			</div>
		  </div>
		@endforeach
	  @else
		<p class="text-center">You have no upcoming assignments</p>
	  @endif
	</div>

		{{-- right side --}}
		<div class="col-md-4 dashboard-right">
			@include('students.recentNotifications')
			@include('partials.recentactivity')
		</div>
	</div>

@endsection
