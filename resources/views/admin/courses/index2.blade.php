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

      
    

    <div class="panel panel-default" style="margin-top: 500px;">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($courses) > 0 ? 'datatable' : '' }} @can('course_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('course_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        @if (Auth::user()->isAdmin())
                            <th>@lang('global.courses.fields.teachers')</th>
                        @endif
                        <th>@lang('global.courses.fields.title')</th>
                        <th>@lang('global.courses.fields.slug')</th>
                        <th>@lang('global.courses.fields.description')</th>
                        <th>@lang('global.courses.fields.price')</th>
                        <th>@lang('global.courses.fields.course-image')</th>
                        <th>@lang('global.courses.fields.start-date')</th>
                        <th>@lang('global.courses.fields.published')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($courses) > 0)
                        @foreach ($courses as $course)
                            <tr data-entry-id="{{ $course->id }}">
                                @can('course_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                @if (Auth::user()->isAdmin())
                                <td>
                                    @foreach ($course->teachers as $singleTeachers)
                                        <span class="label label-info label-many">{{ $singleTeachers->name }}</span>
                                    @endforeach
                                </td>
                                @endif
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->slug }}</td>
                                <td>{!! $course->description !!}</td>
                                <td>{{ $course->price }}</td>
                                <td>@if($course->course_image)<a href="{{ asset('uploads/' . $course->course_image) }}" target="_blank"><img src="{{ asset('uploads/thumb/' . $course->course_image) }}"/></a>@endif</td>
                                <td>{{ $course->start_date }}</td>
                                <td>{{ Form::checkbox("published", 1, $course->published == 1 ? true : false, ["disabled"]) }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.courses.restore', $course->id])) !!}
                                    {!! Form::submit(trans('global.app_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.courses.perma_del', $course->id])) !!}
                                    {!! Form::submit(trans('global.app_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                                                </td>
                                @else
                                <td>
                                    @can('course_view')
                                    <a href="{{ route('admin.lessons.index',['course_id' => $course->id]) }}" class="btn btn-xs btn-primary">@lang('global.lessons.title')</a>
                                    @endcan
                                    @can('course_edit')
                                    <a href="{{ route('admin.courses.edit',[$course->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('course_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.courses.destroy', $course->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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