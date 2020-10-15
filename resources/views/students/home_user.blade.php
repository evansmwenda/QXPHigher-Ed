@extends('layouts.home')

@section('main')

<div class="row">
	<div class="col-lg-8 col-sm-8">
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
			<h3>Units In progress today</h3>
		</div>

		<div class="live-units">
			<div class="row">
				<div class="live-span">
					<h3>Critical & Creative Thinking</h3>
				</div>
				<div class="live-span">
					<i class="fa fa-user"> Michael Joseph</i>
					<p class="text-muted"> Lecturer Name</p>
				</div>
				<div class="live-span">
					<h3>Critical & Creative Thinking</h3>
				</div>
			</div>
			<div class="row text-center">View All</div>
		</div>

		<div class="upcoming">
			<h3>Upcoming Units</h3>
		</div>
		
		<div class="row">
			<div class="upcoming-units">
				<div class="row">
					<div class="live-span">
						<h3>Critical & Creative Thinking</h3>
					</div>
					<div class="live-span">
						<i class="fa fa-user"> Michael Joseph</i>
						<p class="text-muted"> Lecturer Name</p>
					</div>
					<div class="live-span">
						<div class="progress">
							<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>

				</div>
			</div>
			<div class="upcoming-units">
				<div class="row">
					<div class="live-span">
						<h3>Critical & Creative Thinking</h3>
					</div>
					<div class="live-span">
						<i class="fa fa-user"> Michael Joseph</i>
						<p class="text-muted"> Lecturer Name</p>
					</div>
					<div class="live-span">
						<div class="progress">
							<div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
		
	</div>
	{{-- end of main dashboard view --}}
	<div class="col-md-4 dashboard-right">
		<div class="row" style="background: #fff">
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
		</div>
		{{-- end of row --}}
		<div class="row section-2">
			<div class="row">
				<div class="col-sm-10">
					<h3>Recent Activity</h3>
				</div>
				<div class="col-sm-2">
					<i class="fa fa-cog"></i>
				</div>
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<td>All</td>
							<td>Projects</td>
							<td>Assignments</td>
							<td>Meetings</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><i class="fa fa-user"></i></td>
							<td>Michael Joshua</td>
							<td></td>
							<td>ARG</td>
						</tr>
						<tr>
							<td><i class="fa fa-user"></i></td>
							<td>Michael Joshua</i></td>
							<td></td>
							<td>ARG</td>
						</tr>
					</tbody>
				</table>
				<hr style="border: 1px solid rgb(226, 222, 222)">
				<div class="text-center">
					View All
				</div>
			</div>
		</div>
		<div class="row section-2">
			<div class="row">
				<div class="col-sm-10">
					<h3>Recent Activity</h3>
				</div>
				<div class="col-sm-2">
					<i class="fa fa-cog"></i>
				</div>
				<table class="table table-borderless table-striped">
					<thead>
						<tr>
							<td>All</td>
							<td>Projects</td>
							<td>Assignments</td>
							<td>Meetings</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><i class="fa fa-user"></i></td>
							<td>Michael Joshua</td>
							<td></td>
							<td>ARG</td>
						</tr>
						<tr>
							<td><i class="fa fa-user"></i></td>
							<td>Michael Joshua</i></td>
							<td></td>
							<td>ARG</td>
						</tr>
					</tbody>
				</table>
				<hr style="border: 1px solid rgb(226, 222, 222)">
				<div class="text-center">
					View All
				</div>
			</div>
		</div>
	</div>
	{{-- end of Dashboard right-side view --}}
	
</div>

@endsection