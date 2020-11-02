@extends('layouts.home')

@section('sidebar')
    <p class="lead">{{ $lesson->course->title }}</p>

    <div class="list-group">
        @foreach ($lesson->course->publishedLessons as $list_lesson)
            <a href="{{ route('lessons.show', [$list_lesson->course_id, $list_lesson->slug]) }}" class="list-group-item"
                @if ($list_lesson->id == $lesson->id) style="font-weight: bold" @endif>{{ $loop->iteration }}. {{ $list_lesson->title }}</a>
        @endforeach
    </div>
@endsection

@section('main')
<div class="row">
    @include('students.header')
</div>
  <div class="row lesson-details">
      <h2>{{ $lesson->course->title }}</h2>
      <h3>{{ $lesson->title }}</h3>
      @if ($purchased_course || $lesson->free_lesson == 1)
    {!! $lesson->full_text !!}

    @if ($test_exists)
        <hr />
        <h4>This lesson has {{ $questions_count }} Quize (s)</h4>
        
        <a href="{{ route('lessons.attempt', [$lesson->course_id, $lesson->slug]) }}"><button type="button" class="btn btn-primary btn-lg">Attempt Quiz</button></a>
               
    @endif
@else
    Please <a href="{{ route('courses.show', [$lesson->course->slug]) }}">go back</a> and buy the course.
@endif
  </div>

@endsection