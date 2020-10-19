@extends('layouts.home')

@section('main')

<div class="col-lg-8 col-sm-8" style="background: #fff;margin-left:-20px;">
	{{-- top header --}}
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
					 <span class="fa fa-calendar fa-2x"></span>
				</div>
			 </div> 
		
	</div>
	@if (!is_null($purchased_courses))
	<div class="row course-breadcrumb">
		<h3>Courses</h3>
	</div>
   
	<div class="row courses">
		<p>All My Courses</p>
	@foreach($purchased_courses as $course)
		<div class="col-sm-3 col-lg-3 col-md-3">
		   <div class="coarse-list"></div>
		   @for ($star = 1; $star <= 5; $star++)
		   @if ($course->rating >= $star)
			   <span class="glyphicon glyphicon-star"></span>
		   @else
			   <span class="glyphicon glyphicon-star-empty"></span>
		   @endif
	   @endfor
	   <div class="row ">
		<button class="coarse-button">FAVOURITE <i class="fa fa-web"></i></button>
		<button class="coarse-button-2">VIEW <i class="fa fa-arrow-right"></i></button>
	   </div>
	 
		</div>
	@endforeach
	</div>

@endif
<div class="row text-center">
	<button  class="view-all">View All</button>
</div>

<div class="text-center">
	<h3>You can also view the following courses</h3>
	<p>Enhance your literature skills</p>
</div>
</div>
<div class="col-md-4">
	<div class="row header2-top-right">
		<i class="fa fa-user fa-2x"></i> 
			<a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
			   <span class="sr-only">Toggle navigation</span>
			   <span class="fa fa-bars"></span>
		   </a> 

   </div>
</div>
@endsection