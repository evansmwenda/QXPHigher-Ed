@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>

<div class="row">
    {{-- small left side div --}}
    <div class="col-md-4 exams-top" style="background: #fff">
        <h3>View Tests</h3>
        <button data-toggle="modal" data-target="#modalCreateOptions">Select Course</button>                        
        <hr>
        <h4>Course : Biology </h4>
        <hr>
        <form action="">
            <label for="female"><i class="fa fa-check"></i> X-traits of living things</label>
        </form>
    </div>
    {{-- body content on the test view --}}
    <div class="col-md-8 exam-questions">
        <h2>Tests Created</h2>
        <div class="exam-top-buttons"> 
            @can('test_create')   
            <a href="{{ route('admin.tests.create') }}"><button style="background: #060646"><i class="fa fa-check"></i>Create</button></a>  
            @endcan                 
            {{-- <a href="{{ url('/admin /exams/create') }}"><button style="background: #71CA52"><i class="fa fa-check"></i>Edit</button></a>
            <a href="{{ url('/admin /exams/create') }}"><button style="background: #C92519"><i class="fa fa-trash"></i>Delete</button></a>
           <li><a href="{{ route('admin.tests.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">All</a></li> |
            <li><a href="{{ route('admin.tests.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">Trash</a></li>
     --}}
        </div>
        <h3>Critical Thinking</h3>
        <div class="disp-exams">
            <p>1. An arrow in the lower-right corner of a group on the ribbon tells you that
                Advanced which of the following is available?</p>
                <button style="background: #71CA52">Edit</button>
                <button style="background: #C92519">Delete</button> 
        </div>
    </div>
</div>



    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($tests) > 0 ? 'datatable' : '' }} @can('test_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('test_delete')
                            @if ( request('show_deleted') != 1 )<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th>@lang('global.tests.fields.course')</th>
                        <th>@lang('global.tests.fields.lesson')</th>
                        <th>@lang('global.tests.fields.title')</th>
                        <th>@lang('global.tests.fields.description')</th>
                        <th>@lang('global.tests.fields.questions')</th>
                        <th>@lang('global.tests.fields.published')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($tests) > 0)
                        @foreach ($tests as $test)
                            <tr data-entry-id="{{ $test->id }}">
                                @can('test_delete')
                                    @if ( request('show_deleted') != 1 )<td></td>@endif
                                @endcan

                                <td>{{ $test->course->title or '' }}</td>
                                <td>{{ $test->lesson->title or '' }}</td>
                                <td>{{ $test->title }}</td>
                                <td>{!! $test->description !!}</td>
                                <td>{{ $test->questions->count() }}</td>
                                <td>{{ Form::checkbox("published", 1, $test->published == 1 ? true : false, ["disabled"]) }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.tests.restore', $test->id])) !!}
                                    {!! Form::submit(trans('global.app_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.tests.perma_del', $test->id])) !!}
                                    {!! Form::submit(trans('global.app_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                                                </td>
                                @else
                                <td>
                                    @can('test_view')
                                    <a href="{{ route('admin.tests.show',[$test->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('test_edit')
                                    <a href="{{ route('admin.tests.edit',[$test->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('test_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.tests.destroy', $test->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('test_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.tests.mass_destroy') }}'; @endif
        @endcan

    </script>
     <div class="modal fade" id="modalCreateOptions" role="dialog">
        <div class="modal-dialog modal-sm">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body">
                <div class="row select-course">
                    
                <h3>Select Course</h3>
                <select name="course" class="form-control" id="">
                    <option value=""></option>
                </select>
                    <a href="/admin/live-classes/create"><button>Choose</button></a>
                </div>
                
            </div>
          </div>
          
        </div>
      </div>
@endsection