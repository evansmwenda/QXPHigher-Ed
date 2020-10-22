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
        	<h3>Degree Progress Audit</h3>
        	<p>
        		All courses required to complete your program are listed below. Select a column heading to sort your
        		 courses by status, letter grade and term.If you have questions regarding your degree audit, 
        		please contact your advisor.
        	</p>
        </div>
        <div class="col-sm-12 col-md-7" >
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
        </div>
        <div class="col-sm-12 col-md-5">
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
        </div>
    </div>
@endsection