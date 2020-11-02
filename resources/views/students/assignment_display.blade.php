@extends('layouts.home')

@section('main')
	<div class="row">
		@include('students.header')
	</div>
	<div class="row" style="background: #fff;padding-left:20px; color:#060646">
	<h2 style="font-weight: 900;">Current Assignments</h2>
	</div>
<div class="row" style="background: #fff; height:600px; overflow-y:scroll">
	
	<div class="col-md-3">
		@foreach ($all_assignments as $item)
		<div class="assignments" onclick="location.href='{{ url('assignments/attempt/'.$item->id) }}';">
			<h2>{{ $item->title }}</h2>
		</div>
		@endforeach


	</div>
	<div class="col-md-9 quizes-all">
		@if(!is_null($assignment))
		<div class="quize-header" >
			<h3>{{ $assignment->title }}</h3>
		</div>
		<br>
		<a href="{{url('uploads/assignments/'.$assignment->course->slug.'/'.$assignment->media)}}" download><button class="btn btn-primary">Dowload file</button></a></p>
		<p style="padding-top: 20px;">Once completed, you can submit the assignment from the section below</p>
		<form role="form" enctype="multipart/form-data" method="post" action="{{('/assignments/attempt/'.$assignment->id)}}"> {{csrf_field() }}
			<div class="form-group">
			<input type="hidden" id="assignment_id" name="assignment_id" value="{{ $assignment->id }}">
			<input type="hidden" id="slug" name="slug" value="{{ $assignment->course->slug }}">
			<label for="exampleInputFile">Choose Assignment</label>
			<div class="input-group">
				<div class="custom-file">
				<input type="file" name="assignment" class="custom-file-input form-control" id="assignment" required>
				</div>
			</div>
			</div>
			<?php 
			if(!is_null($submitted)){
				echo "<p style='color:green'>You have already submitted this assignment</p>";
			}else{
				?>
				<button type="submit" class="btn btn-primary">Submit</button>
				<?php
			}
			?>
			
		</form>
		@endif
	</div>
</div> 
@endsection
