@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
    @if(Session::has("flash_message_error")) 
        <div class="alert alert-error alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_error') !!}</strong>
        </div> 
    @endif 

    @if(Session::has("flash_message_success")) 
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_success') !!}</strong>
        </div> 
    @endif

    <div class="col-md-8 col-sm-12">
        <h3 class="page-title">@lang('global.lessons.title')
            @can('lesson_create')
                <a href="{{ url('/admin/lessons/create') }}" style="display: inline-block" class="my-btn my-btn-warning">
                    <span class="fa fa-plus" style="margin-left: 0px !important"></span>Add
                </a>
            @endcan
        </h3>
        @if(count($lessons) > 0)
            @foreach($lessons as $lesson)
            <div class="assignment-card">
                <div class="col-sm-3 col-md-2 text-center" style="">
                <img src="https://placehold.it/80" style="padding-top:3px;"/>
                </div>
                <div class="assignment-text col-sm-8 col-md-8" style="">
                <p style="font-size: 1.3em;">{{ $lesson->title}}</p>
                <p style="font-size: 1em;">{{ $lesson->created_at}}</p>
                <p style="font-size: .8em;color:grey">{{ $lesson->course->title}}</p>
                </div>
                <div class="col-sm-1 col-md-2 text-center" style="">
                <a href="{{ url('admin/lessons/'.$lesson->id.'/edit') }}" class="btn btn-info assignment-link" style="">Edit</a>
                </div>
            </div>
            @endforeach
        @else
            <p class="text-center">You have no lessons</p>
        @endif
    </div>
    <div class="col-md-4 col-sm-12"></div>

    

    {{-- <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.lessons.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">All</a></li> |
            <li><a href="{{ route('admin.lessons.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">Trash</a></li>
        </ul>
    </p> --}}
    
@stop

@section('javascript') 
    <script>
        @can('lesson_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.lessons.mass_destroy') }}'; @endif
        @endcan

    </script>
@endsection