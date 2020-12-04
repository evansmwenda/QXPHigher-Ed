@extends('layouts.home')

@section('main')
<div class="row">
	@include('students.header')
</div>

	<div class="col-lg-8 col-sm-8">
		<div class="qxp-back">
			
				<div class="custom-search">
				<p>Your account expires on {{$expiry_on}}</p>
				</div>
				<a href="/subscribe"><button class="custom-search-button">Renew</button></a>

		    <div class="row light-bg">
				<button onclick="showAll()">Show All</button>
				<button onclick="ongoing()">Ongoing <span style="background: #71CA52" class="fa fa-check"></span></button>
				<button id="comp" onclick="completed()">Completed <span style="background: #FD6C03" class="fa fa-check"></span></button>
			</div>
			<h3>Recent Courses</h3>
		</div>
		@if(count($enrolled_course)>0)
			<!-- //we have enrolled to courses -->
			@foreach($enrolled_course->take(5) as $key=>$course)
				<div class="live-units" id="showAll">
					<div class="row">
						<div class="live-span">
							<i class="fa fa-graduation-cap"> {{ $course->title}}</i>
						<p class="text-muted"> Updated at {{$course->update_at}}</p>
						</div>
						<div class="live-span">
							<i class="fa fa-book"> Course Progress</i>
							<p class="text-muted">
								
								<?php
								  foreach($enrolled_course as $course){
									//get the lesson ids and calculate percentage done
									$ids = explode(",", $course->lesson_id);
									$count = count(array_unique($ids));
									if($course->total_lessons > 0){
										$percentage= ($count/$course->total_lessons)*100;
										echo $percentage.'%';
									}else{
										
										echo '0%';
									}
								  }
								?>
							</p>
						</div>
						<div class="live-span">
							<i class="fa fa-usser">Course Lessons</i>
							<p class="text-muted"> {{$course->total_lessons}}</p>
						
						</div>
					</div>
				</div>
				<div style="margin-top:40px;">&nbsp;</div>
			@endforeach

		@else
			<!-- //not enrolled to any courses -->
			<p class="text-center">You are not enrolled to any course.</p>
		@endif

		{{-- for ongoing --}}
		@if(count($enrolled_course)>0)
			<!-- //we have enrolled to courses -->
			@foreach($enrolled_course->take(5) as $key=>$course)
			
			<?php
			foreach($enrolled_course as $course){
			  //get the lesson ids and calculate percentage done
			  $ids = explode(",", $course->lesson_id);
			  $count = count(array_unique($ids));
			  if($course->total_lessons > 0){
				// there arebeing done with lessons courses 
				?>
				<div class="live-units" style="display: none" id="ongoing">
					<div class="row">
						<div class="live-span">
							<i class="fa fa-graduation-cap"> {{ $course->title}}</i>
						<p class="text-muted"> Updated at {{$course->update_at}}</p>
						</div>
						<div class="live-span">
							<i class="fa fa-book"> Course Progress</i>
							<p class="text-muted">
								<?php
									foreach($enrolled_course as $course){
									//get the lesson ids and calculate percentage done
									$ids = explode(",", $course->lesson_id);
									$count = count(array_unique($ids));
									if($course->total_lessons > 0){
										$percentage= ($count/$course->total_lessons)*100;
									}else{
										
										echo '0%';
									}
									}
								?>
							</p>
						</div>
						<div class="live-span">
							<i class="fa fa-usser">Course Lessons</i>
							<p class="text-muted"> {{$course->total_lessons}}</p>
						
						</div>
					</div>
				</div>
				<?php
			  }else{

			  }
			}
		  ?>


				<div style="margin-top:40px;">&nbsp;</div>
			@endforeach

		@else
			<!-- //not enrolled to any courses -->
			<p class="text-center">You are not enrolled to any course.</p>
		@endif
{{-- COMPLETED --}}

		@if(count($enrolled_course)>0)
		<!-- //we have enrolled to courses -->
		@foreach($enrolled_course->take(5) as $key=>$course)
			<?php
			foreach($enrolled_course as $course){
				//get the lesson ids and calculate percentage done
				$ids = explode(",", $course->lesson_id);
				$count = count(array_unique($ids));
				$percentage='';
				if($course->total_lessons > 0){
					$percentage= ($count/$course->total_lessons)*100;
				}
				if ($percentage=='100') {
					?>
					<div class="live-units" style="display: none" id="completed">
						<div class="row">
							<div class="live-span">
								<i class="fa fa-graduation-cap"> {{ $course->title}}</i>
							<p class="text-muted"> Updated at {{$course->update_at}}</p>
							</div>
							<div class="live-span">
								<i class="fa fa-book"> Course Progress</i>
								<p class="text-muted">
										100%
								</p>
							</div>
							<div class="live-span">
								<i class="fa fa-usser">Course Lessons</i>
								<p class="text-muted"> {{$course->total_lessons}}</p>
							
							</div>
						</div>
					</div>
					<?php
				}
			}
		?>


			<div style="margin-top:40px;">&nbsp;</div>
		@endforeach

		@else
		<!-- //not enrolled to any courses -->
		<p class="text-center">You are not enrolled to any course.</p>
		@endif
	</div>
	{{-- end of main dashboard view --}}
	<div class="col-md-4 dashboard-right">
		@include('students.recentNotifications')
		@include('partials.recentactivity')
	</div>
	{{-- end of Dashboard right-side view --}}
<script>
function ongoing(){
	document.getElementById("showAll").style.display = "none";
	document.getElementById("ongoing").style.display = "block";
	document.getElementById("showAll").style.display = "none";

	// button cullor
	

}
function showAll(){
	document.getElementById("showAll").style.display = "block";
	document.getElementById("ongoing").style.display = "none";
	document.getElementById("showAll").style.display = "none";
	
}
function completed(){

	document.getElementById("showAll").style.display = "none";
	document.getElementById("completed").style.display = "block";
	document.getElementById("ongoing").style.display = "none";

	document.getElementById("comp").style.background = "#FD6C03";

}
</script>
@endsection