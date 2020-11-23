@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
    <div class="row" style="background: #fff">
        <div class="row">
            <div class="col-md-8 course-top">
                <h3>@lang('global.courses.title')</h3>
                <h4>Running Courses</h4>
            </div>
            <div class="col-md-4 course-top">
               
                    <a href="{{ route('admin.courses.create') }}"><button><i class="fa fa-plus"></i> Add Course</button></a>                      
                
            </div>
        </div>
        {{-- courses list --}}
        <div class="col-md-8">
            @foreach($courses as $course)
            <div class="col-sm-12 col-lg-3 col-md-3" style="margin-bottom: 20px;">
                <a href="{{url('admin/courses/'.$course->id.'/edit')}}"><div class="coarse-list"></div></a>
                <a href="{{url('admin/courses/'.$course->id.'/edit')}}"><p style="color:#060646;margin: 0px !important">{{$course->title}}</p></a>
                @for ($star = 1; $star <= 5; $star++)
                @if ($course->rating >= $star)
                    <span class="glyphicon glyphicon-star" style="font-size: 10px;"></span>
                @else
                    <span class="glyphicon glyphicon-star-empty" style="font-size: 10px;"></span>
                @endif
                @endfor

                    <div class="row ">
                        <a href="{{url('admin/lessons?course_id='.$course->id)}}">
                            <button class="coarse-button" style="height:30px;">LESSONS <i class="fa fa-web"></i></button>
                        </a>
                        
                        <a href="{{url('admin/courses/'.$course->id.'/edit')}}">
                            <button class="coarse-button-2" style="height: 30px;">EDIT 
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </a>
                    </div>
            </div>
            @endforeach
        </div>
        {{-- right side --}}
        <div class="col-md-4">
            {{-- @include('admin.recents') --}}
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('course_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.courses.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection