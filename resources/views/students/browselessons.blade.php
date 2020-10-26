@extends('layouts.home')

@section('main')
	<div class="row">
		<div class="lessons-bg" style="">
			<div class="lessons-overlay"></div>
			<div class="container" style="">
				<div class="row">
					<div class="col-sm-12 col-md-5">
						<h1 style="color:#ffffff">My Classes</h1>
						<h4 style="color:#ffffff">Hello Student, welcome back!</h4>
					</div>
				</div>
			</div>
			
		</div>
        <div class="col-md-12 col-sm-12">
        	<h1 style="color: #060646">Degree Progress Audit</h1>
        	<h4 style="color: #060646">
        		All courses required to complete your program are listed below. Select a column heading to sort your
        		 courses by status, letter grade and term.If you have questions regarding your degree audit, 
        		please contact your advisor.
        	</h4>
        </div>
        <div class="col-sm-12 col-md-12" >
        	<div class="card" style="border: 1px solid #00CED1;border-radius:6px;width: 100%">
        		<table class="table table-borderless" style="width: 100%">
        			@if(count($my_classes) > 0)
        				<thead>
							<tr style="font-weight: bold;">
								<td style="color: #060646">Course</td>
								<td style="color: #060646">Name</td>
								<td style="color: #060646">Status</td>
								<td style="color: #060646">Term</td>
							</tr>
						</thead>
						<tbody>
							@foreach($my_classes as $class)
								<tr>
									<td>{{$class->course->title}}</td>
									<td>{{$class->title}}</td>
									<td>Scheduled</td>
									<td>{{$class->classTime or 'N/A'}} </td>
								</tr>
							@endforeach
						</tbody>
					@else
						<p class="text-center">You have no scheduled classes</p>
					@endif

				</table>
        	</div>
        </div>
       <!--  <div class="col-sm-12 col-md-5">
        	<div class="card" style="border: 1px solid #00CED1;border-radius:6px;width: 100%">
        		<table class="table table-borderless" style="width: 100%">
					<thead>
						<tr style="font-weight: bold;">
							<td>Course</td>
							<td>Name</td>
							<td>Status</td>
							<td>Term</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Biology 101</td>
							<td>Live Class Meeting</td>
							<td>Scheduled</td>
							<td>2020-09-12</td>
						</tr>
						<tr>
							<td>BASIC2-D DESIGN</td>
							<td>Live Class Meeting</td>
							<td>Scheduled</td>
							<td>2019/20/SEM2</td>
						</tr>
						<tr>
							<td>BASIC2-D DESIGN</td>
							<td>Live Class Meeting</td>
							<td>Scheduled</td>
							<td>2020-09-12</td>
						</tr>
						<tr>
							<td>Biology 101</td>
							<td>Live Class Meeting</td>
							<td>Scheduled</td>
							<td>2019/20/SEM2</td>
						</tr>
					</tbody>
				</table>
        	</div>
        </div> -->
    </div>
@endsection