@extends('layouts.home')

@section('main')
<div class="row">
	<div class="col-md-8" style="background-color: red">
		<div class="row top-header-2">

			<div class="col-md-12 col-sm-12" >
				<div class="col-sm-6">
					<div class="form-group has-search">
						<input type="text" class="form-control" placeholder="Search">
					  </div>
				</div>
				<div class="col-sm-2">
					<span class="fa fa-shield-alt fa-2x"></span>
				</div>
				<div class="col-sm-2">
					 <span class="fa fa-bell fa-2x"></span>
				</div>
				<div class="col-sm-2">
					 <span class="fa fa-calendar-alt fa-2x"></span>
				</div>
			 </div> 
		
		</div>
	</div>
	<div class="col-md-4 dashboard-right" style="background-color: green">
		<div class="row top-right">
			<i class="fa fa-user fa-2x"></i> 
				<a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
				   <span class="sr-only">Toggle navigation</span>
				   <span class="fa fa-bars"></span>
			   </a> 

	   </div>
	</div>
</div>
<div class="row">
	@if($active)
		<div class="col-md-8">
			@if (!is_null($purchased_courses))
				<div class="row course-breadcrumb">
					<h3>Courses</h3>
				</div>
			
				<div class="row courses">
					<p>All My Courses</p>
					@foreach($purchased_courses as $course)
						<div class="col-sm-12 col-lg-3 col-md-3" style="margin-bottom: 20px;">
						<a href="{{url('course/'.$course->slug)}}"><div class="coarse-list"></div></a>
						<a href="{{url('course/'.$course->slug)}}"><p style="color:#060646;margin: 0px !important">{{$course->title}}</p></a>
						@for ($star = 1; $star <= 5; $star++)
						@if ($course->rating >= $star)
							<span class="glyphicon glyphicon-star" style="font-size: 10px;"></span>
						@else
							<span class="glyphicon glyphicon-star-empty" style="font-size: 10px;"></span>
						@endif
						@endfor

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