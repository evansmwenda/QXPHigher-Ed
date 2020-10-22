<div class="row section-2 recent-activity">
	<div class="row">
		<div class="col-sm-11">
			<h3>Quizzes</h3>
			<table class="table table-borderless table-striped">
				@if(count($test_details)<=0)
					  <p style="text-align: center">You have no Quizzes</p> 
				@else
					<thead>
						<tr>
							<td>#</td>
							<td>Quiz</td>
							<td>Score&nbsp;&nbsp;&nbsp;</td>
						</tr>
					</thead>
					<tbody>
						@foreach($test_details->take(3) as $key=>$test)
							<tr>
								<td>{{++$key}}</td>
								<td>
									<p style="margin-bottom: 0px !important">{{$test->title}}</p>
									<span style="font-size: .75em;color: grey;padding-top: 0px !important;">{{ $test->name }}</span>
								</td>
								<td><span class="badge qxp-bg-info" style="float: left;">{{ $result_array[$test->test_id]}}</span></td>
							</tr>
						@endforeach
						
						
					</tbody>
				@endif
			</table>
			<div class="text-center col-12">
				<a href="{{url('/exams')}}"><p>View All Quizzes</p></a>
			</div>
		</div>

	</div>
</div>
<div class="row section-2 recent-activity">
	<div class="row">
		<div class="col-sm-11">
			<h3>Assignments</h3>
			<table class="table table-borderless table-striped">
				@if(count($assignments)<=0)
					  <p style="text-align: center">You have no Assignments</p> 
				@else
					<thead>
						<tr>
							<td>#</td>
							<td>Name</td>
							<td>Due Date&nbsp;&nbsp;</td>
						</tr>
					</thead>
					<tbody>
						@foreach($assignments->take(4) as $key=>$assignment)
							<tr>
							  <td>{{++$key}}</td>
							  <td style="padding-right: 10px !important;">
								<p style="margin-bottom: 0px !important;padding-right: 10px !important;">{{$assignment['title']}}</p>
								<span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{$assignment->course->title}}</span>
							  </td>
							  <td>
								<p>12-08-2019</p> 
							  </td>
							</tr>
						@endforeach
					</tbody>
				@endif
				
			</table>
			<div class="text-center">
				<a href="{{url('/assignments')}}"><p>View All Assignments</p></a>
			</div>
		</div>
	</div>
</div>