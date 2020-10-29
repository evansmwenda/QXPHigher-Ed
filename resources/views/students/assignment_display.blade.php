@extends('layouts.home')

@section('main')
<div class="row">
	<div class="col-md-8" >
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
	{{-- right side --}}
	<div class="col-md-4 dashboard-right">
		<div class="row top-right">
			<i class="fa fa-user fa-2x"></i> 
				<a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
				   <span class="sr-only">Toggle navigation</span>
				   <span class="fa fa-bars"></span>
			   </a> 

	   </div>
	   {{-- @include('partials.recentactivity') --}}
  </div>
</div>


  <div class="row">

	  @if(Session::has("flash_message_error")) 
	  <div class="alert alert-error alert-block">
		  <button type="button" class="close" data-dismiss="alert">x</button>
		  <strong>{!! session('flash_message_error') !!}</strong>
	  </div> 
	  @endif 

	  @if(Session::has("flash_message_success")) 
		  <div class="alert alert-success alert-block">
			  <button type="button" class="close" data-dismiss="alert">x</button>
			  <strong>{!! session('flash_message_success') !!}</strong>
		  </div> 
	  @endif


		<div class="col-sm-12 col-md-12" style="padding-top:20px;">
			@if(!is_null($assignment))
				<div class="panel panel-default">
					<div class="panel-body">
						<h4>{{ $assignment->title }}</h4><span>{{ $assignment->course->title}}</span><br>
						{{ $assignment->description }}<br>
					<a href="{{url('uploads/assignments/'.$assignment->course->slug.'/'.$assignment->media)}}" download>Download File</a>
					<p style="padding-top: 20px;">Once completed, you can submit the assignment from the section below</p>
					<form role="form" enctype="multipart/form-data" method="post" action="{{('/assignments/attempt/'.$assignment->id)}}"> {{csrf_field() }}
						<div class="form-group">
						<input type="hidden" id="assignment_id" name="assignment_id" value="{{ $assignment->id }}">
						<input type="hidden" id="slug" name="slug" value="{{ $assignment->course->slug }}">
						<label for="exampleInputFile">Choose Assignment</label>
						<div class="input-group">
							<div class="custom-file">
							<input type="file" name="assignment" class="custom-file-input" id="assignment" required>
							</div>
						</div>
						</div>
						<?php 
						if(!is_null($submitted)){
							echo "<p style='color:green'>You have already submitted for this assignment</p>";
						}
						?>



						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
					</div>
				</div>
			@else
				<div class="panel-heading">
				<h4 class="panel-body">
					<p>You don't have any assignments</p>
				</h4>
				</div>
			@endif
			<a href="/assignments" class="btn btn-default" style="margin-bottom: 20px;">Back to List</a>
		</div>
</div>


  
  

	  
@endsection
