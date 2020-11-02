@extends('layouts.home')

@section('main')
<div class="row">
    @include('students.header')
</div>
<div class="row" style="background: #fff">
    @if(Session::has("flash_message_error")) 
    <div class="alert alert-error alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>{!! session('flash_message_error') !!}</strong>
    </div> 
  @endif 

    @if(Session::has("flash_message_success")) 
    <div class="alert progress-bar-success alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong style="color:white;">{!! session('flash_message_success') !!}</strong>
    </div> 
    @endif

  <div class="course-details">
      <div class="row">
          <div class="col-md-6">
            <h2>{{ $course->title }}</h2>
          </div>
          {{-- <div class="col-md-4 pull-right">
            @if ($purchased_course)
            Rating: {{ $course->rating }} / 5
            
            <form action="{{ route('courses.rating', [$course->id]) }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                <div class="col-md-8">
                    <select name="rating" class="form-control col-md-4">
                        <option value="1">1 - Awful</option>
                        <option value="2">2 - Not too good</option>
                        <option value="3">3 - Average</option>
                        <option value="4" selected>4 - Quite good</option>
                        <option value="5">5 - Awesome!</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="submit" class="btn btn-primary" value="Rate" />
                </div>
                </div>

               
            </form>
            
            @endif
          </div> --}}
      </div>
    <span>{{ $course->description }}</span>

     {{-- ENROLL TO COURSE BUTTON--}}
    <p>
        @if (\Auth::check())
            @if($course->price == null)
                {{-- <a href="/enroll/{{$course->id}}"
               class="btn btn-primary">Enroll course Now</a> --}}
            @endif   

            @if ($course->students()->where('user_id', \Auth::id())->count() == 0 &&$course->price != null)
            <form action="{{ route('courses.payment') }}" method="POST">
                <input type="hidden" name="course_id" value="{{ $course->id }}" />
                <input type="hidden" name="amount" value="{{ $course->price * 100 }}" />
                <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('PUB_STRIPE_API_KEY') }}"
                    data-amount="{{ $course->price * 100 }}"
                    data-currency="usd"
                    data-name="QXP LMS"
                    data-label="Buy course (${{ $course->price }})"
                    data-description="Course: {{ $course->title }}"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="auto"
                    data-zip-code="false">
                </script>
                {{ csrf_field() }}
            </form>
            @endif
            
        @else
            {{-- <a href="{{ route('auth.register') }}?redirect_url={{ route('courses.show', [$course->slug]) }}"
               class="btn btn-primary">Buy course (${{ $course->price }})</a>    --}}
        @endif
    </p>
    {{-- END OF ENROLL TO COURSE--}}

    {{-- SUB-TOPICS OF THE COURSE --}}
    <div class="row">
        <h4 style="color: #060646; margin-left:10px">Available Topics</h4>
        @foreach ($course->publishedLessons as $lesson)
            <div class="sub-titles" onclick="location.href='{{ route('lessons.show', [$lesson->course_id, $lesson->slug]) }}';">
                <div class="sub-header">
                    <a href="{{ route('lessons.show', [$lesson->course_id, $lesson->slug]) }}">{{ $loop->iteration }}.  {{substr($lesson->title , 0, 40) }}</a>
               
                </div>
                @if ($lesson->free_lesson)
        
                @endif 
            
                <p>{{ substr ($lesson->short_text,0,200) }}</p>
                
            </div>
        @endforeach
    </div>
    {{-- END OF COURSE SUB TOPICS --}}

  </div>

</div>

@endsection