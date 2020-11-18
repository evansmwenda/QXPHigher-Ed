@extends('layouts.home')

@section('main')
<div class="row">
  @include('students.header')
</div>
<div class="row" style="background: #fff">
	@if($active)
		<div class= "col-md-8">
			@if (!is_null($purchased_courses))
				<div class="row course-breadcrumb">
					<h3>Enrolled Courses</h3>
				<a href="{{url('/courses/search')}}"><button class="pull-right">Search Course</button></a>
				</div>
			
				<div class="row courses">
					<p>All My Courses</p>
					@foreach($enrollments as $course)
						<div class="col-sm-12 col-lg-3 col-md-3" style="margin-bottom: 20px;">
						<a href="{{url('course/'.$course->slug)}}"><div class="coarse-list"></div></a>
						<a href="{{url('course/'.$course->slug)}}"><p style="color:#060646;margin: 0px !important">{{$course->title}}</p></a>
						{{-- @for ($star = 1; $star <= 5; $star++)
						@if ($course->rating >= $star)
							<span class="glyphicon glyphicon-star" style="font-size: 10px;"></span>
						@else
							<span class="glyphicon glyphicon-star-empty" style="font-size: 10px;"></span>
						@endif
						@endfor --}}

							<div class="row ">
								<button class="coarse-button" style="height:30px;">FAVOURITE <i class="fa fa-web"></i></button>
								<a href="{{url('course/'.$course->slug)}}">
									<button class="coarse-button-2" style="height: 30px;">VIEW 
										<i class="fa fa-arrow-right"></i>
									</button>
								</a>
							</div>
					
						</div>
					@endforeach
				</div>
			@endif
			<div class="row text-center">
				<button  class="view-all">View All</button>
			</div>

			<div class="row other-courses">
				<h3>You can also view the following courses</h3>
				<p>Enhance your literature skills</p>
			</div>

		</div>
		<div class="col-md-4">
			@include('partials.recentactivity')
		</div>
	@else
		<div class="row text-center" style="">
			<p>Your subscription has expired, please click the button below to renew </p>
			<a href="/subscribe" class="btn btn-warning">Renew</a>
			{{-- <button  class="view-all">View All</button> --}}
		</div>
	@endif
	
</div>

	{{-- right side --}}
	
@endsection