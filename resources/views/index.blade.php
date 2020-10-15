@extends('layouts.home')

@section('main')

<div class="row">
    <div class="col-md-8">
    @if (!is_null($purchased_courses))
        <div class="row course-breadcrumb">
            <h3>Courses</h3>
        </div>
       
        <div class="row">
        @foreach($purchased_courses as $course)
            <div class="col-sm-3 col-lg-3 col-md-3">
                <div class="thumbnail">
                    @if($course->course_image)
                    {{-- <img src="{{asset('uploads/'.$course->course_image)}}" alt="{{ 
                    $course->title }}" style="width: 320px;height: 150px;"> --}}
                    <img src="" alt="{{ $course->title }}">
                    @else
                    <img src="https://placehold.it/320x150" alt="{{ 
                    $course->title }}">
                    @endif

                    <div class="ratings">
                        <p>Progress: {{ Auth::user()->lessons()->where('course_id', $course->id)->count() }}
                            of {{ $course->lessons->count() }} lessons</p>
                    </div>
                    <div class="captions">
                        <h4><a href="{{ route('courses.show', [$course->slug]) }}">{{ $course->title }}</a>
                        </h4>
                        {{-- <p>{{ \Illuminate\Support\Str::limit($course->description, 200, $end='...') }}</p> --}}
                    </div>

                </div>
            </div>
        @endforeach
        </div>

    @endif

    <div class="row other-courses">
        <h3>You can also view the following courses</h3>
        <p>Enhance your literature skills</p>
    </div>

    <div class="row">
    @foreach($courses as $course)
        <div class="col-sm-3 col-md-3 col-lg-3">
            <div class="thumbnail">
                @if($course->course_image)
                    {{-- <img src="{{asset('uploads/'.$course->course_image)}}" alt="{{ 
                $course->title }}" style="width: 320px;height: 150px;"> --}}
                <img src="https://placehold.it/320x150" alt="{{ 
                    $course->title }}">
                @else
                <img src="https://placehold.it/320x150" alt="{{ 
                    $course->title }}">
                @endif
                <div class="caption">
                    <h4 class="pull-right">${{ $course->price }}</h4>
                    <h4><a href="{{ route('courses.show', [$course->slug]) }}">{{ $course->title }}</a>
                    </h4>
                    {{-- <p>{{ \Illuminate\Support\Str::limit($course->description, 200, $end='...') }}</p> --}}
                </div>
                <div class="ratings" style="height: 35px;">
                    <p class="pull-right">Students: {{ $course->students()->count() }}</p>
                    <p>
                        @for ($star = 1; $star <= 5; $star++)
                            @if ($course->rating >= $star)
                                <span class="glyphicon glyphicon-star"></span>
                            @else
                                <span class="glyphicon glyphicon-star-empty"></span>
                            @endif
                        @endfor
                    </p>
                </div>
            </div>
        </div>
    @endforeach
    </div>
    </div>
	<div class="col-md-4 dashboard-right">
		<div class="row">
			<div class="row">
				<div class="col-sm-8 qxp-center">
					<input type="text" class="form-control" placeholder="Your Course">
				</div>
				<div class="col-sm-4">
					<button class="btn btn-warning">View</button>
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
</div>

@endsection