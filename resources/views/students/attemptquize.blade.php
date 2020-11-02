@extends('layouts.home')

@section('main')
    <div class="row">
        @include('students.header')
    </div>
    <div class="row" style="background: #fff;color:#060646; padding-left:20px"> <h2>Quize Attempt</h2></div>
    <div class="row quizes">
        <div class="col-md-3">
            <div class="quize">
                <h2>{{ $lesson->course->title }}</h2>
                <h3>{{ $lesson->test->title }}</h3>
               <p> Author : John Doe</p>
               
            </div>
            <div class="otherAssignments">
                <h3>Other Assingments</h3>
                <div class="quize">
                    <h2>{{ $lesson->course->title }}</h2>
                    <h3>{{ $lesson->test->title }}</h3>
                   <p> Author : John Doe</p>
                   
                </div>
                <div class="quize">
                    <h2>{{ $lesson->course->title }}</h2>
                    <h3>{{ $lesson->test->title }}</h3>
                   <p> Author : John Doe</p>            
                </div>
            </div>
        </div>
        <div class="col-md-9 quizes-all">
            <div class="quize-header">
                <h3>{{ $lesson->test->title }}</h3>
            </div>
            
            @if (!is_null($test_result))
                <h3>You have already completed the quize</h3>
                <i>Your test score was: {{ $test_result->test_result }}</i>
            @else
            <form action="{{ route('lessons.test', [$lesson->slug]) }}" method="post">
                {{ csrf_field() }}
                <h2>POP QUIZE</h2>
                @foreach ($lesson->test->questions as $question)
                    <p>{{ $loop->iteration }}. {{ $question->question }}</p>
                    @foreach ($question->options as $option)
                        <input type="radio" name="questions[{{ $question->id }}]" value="{{ $option->id }}" /> {{ $option->option_text }}<br />
                    @endforeach
                    <br />
                @endforeach
                <input type="submit" class="submit" value=" Submit results " />
            </form>
            @endif
        </div>

    </div>
@endsection