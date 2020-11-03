@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
    <div class="row">
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
            {{-- top bar --}}
            <div class="row">
                <div class="col-md-8 course-top">
                    <h3>@lang('global.lessons.title')</h3>
                
                </div>
                <div class="col-md-4 course-top">
                        <a href="{{ route('admin.lessons.create') }}"><button><i class="fa fa-plus"></i> Add Course</button></a>                      
                </div>
            </div>
            {{-- col-md-8 --}}
            <div class="col-md-8">
                @if(count($lessons) > 0)
                @foreach($lessons as $lesson)
                <div class="assignment-card">
                    <div class="col-sm-3 col-md-2 text-center">
                    <img src="https://placehold.it/60" style="padding-top:8px;"/>
                    </div>
                    <div class="assignment-text col-sm-8 col-md-8" >
                        <h3>{{ $lesson->title}}</h3>
                        <span class="text-muted">{{ $lesson->course->title}} - {{ $lesson->created_at}}</span>
                    {{-- <p style="font-size: 1em;"></p>
                    <i style="font-size: 1em;">{{ $lesson->created_at}}</i>
                    <p style="font-size: .8em;color:grey">{{ $lesson->course->title}}</p>--}}
                    </div> 
                    <div class="col-sm-1 col-md-2 text-center" style="">
                    <a href="{{ url('admin/lessons/'.$lesson->id.'/edit') }}"><button>Edit Lesson</button></a>
                    </div>
                </div>
                @endforeach
                @else
                    <p class="text-center">You have no lessons</p>
                @endif
            </div>
            {{-- Right side content --}}
            <div class="col-md-4">
                
            </div>
            
    </div>

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