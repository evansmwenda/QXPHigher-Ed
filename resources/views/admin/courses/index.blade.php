@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
    <h3 class="page-title">@lang('global.courses.title')</h3>
    @can('course_create')
    <p>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
        
    </p>
    @endcan
    <div class="col-sm-12 col-md-8">
        <div class="row courses">
            <p>All My Courses</p>
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
    </div>
    <div class="col-sm-12 col-md-4"></div>
@stop

@section('javascript') 
    <script>
        @can('course_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.courses.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection