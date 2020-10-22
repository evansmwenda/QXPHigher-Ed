@extends('layouts.home')

@section('main')


<div class="row">
	<div class="col-lg-8 col-sm-8">
		{{-- top header --}}
		<div class="top-header">
			<div class="row">
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
		</div>

		<div class="qxp-back">
			
				<div class="custom-search">
					<p>You have 3 days left for subscriprion</p>
				</div>
				<button class="custom-search-button">Upgrade</button>

		    <div class="row light-bg">
				  <p>Show All</p>
				 <button>All</button>
				<button>Confirmed</button>
			</div>
			<h3>Recent Courses</h3>
		</div>
		@if(count($enrolled_course)>0)
			<!-- //we have enrolled to courses -->
			@foreach($enrolled_course->take(5) as $key=>$course)
				<div class="live-units">
					<div class="row">
						<div class="live-span">
							<h3>{{ $course->title}}</h3>
						</div>
						<div class="live-span">
							<i class="fa fa-user"> Michael Joseph</i>
							<p class="text-muted"> Lecturer Name</p>
						</div>
						<div class="live-span">
							<div class="progress">
								<div class="progress-bar progress-bar-warning" role="progressbar" style="width: 85%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							
							</div>
						
						</div>
					</div>
				</div>
				<div style="margin-top:40px;">&nbsp;</div>
			@endforeach

		@else
			<!-- //not enrolled to any courses -->
			<p class="text-center">You are not enrolled to any course.</p>
		@endif
		
	</div>
	{{-- end of main dashboard view --}}
	<div class="col-md-4 dashboard-right">
		<div class="row top-right">
			 <i class="fa fa-user fa-2x"></i> 
			     <a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="fa fa-bars"></span>
				</a> 

		</div>

<!-- 		<div class="row" style="background: #fff">
			<h3>Units</h3>
			<hr style="border: 1px solid rgb(226, 222, 222)">
			<div class="row">
				<div class="col-sm-4 qxp-center">
					<button style="background: green">21</button>
					<p style="color: green">Confirmed</p>
				</div>
				<div class="col-sm-4 qxp-center">
					<div class="col-sm-4 qxp-center">
						<button style="background: #FD6C03">21</button>
						<p style="color: #FD6C03">Pending</p>
					</div>
				</div>
				<div class="col-sm-4 qxp-center">
					<div class="col-sm-4 qxp-center">
						<button style="background: #C92519">21</button>
						<p  style="color: #C92519">Cancelled</p>
					</div>
				</div>
			</div>
		</div> -->
		{{-- end of row --}}
		@include('partials.recentactivity')
		
	</div>
	{{-- end of Dashboard right-side view --}}
	
</div>

@endsection