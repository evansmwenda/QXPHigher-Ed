@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<div class="row">
    @include('students.header')
</div>

<div class="row">
    {{-- small left side div --}}
    <div class="col-md-4 exams-top" style="background: #fff">
        <h3>View Quizzes</h3>
        <button data-toggle="modal" data-target="#modalCreateOptions">Select Course</button>                        
        <hr>
        <h4>Course : {{$course_title or 'Select Course'}} </h4>
        <hr>
            @if(count($titles_array)>0)
                @foreach($titles_array as $exam)
                    <a href="{{url('/admin/tests/'.$exam->id)}}">
                        <label for="female">
                            <i class="fa fa-check"></i> 
                            <span type="submit">{{$exam->title}}</span>
                        </label>
                    </a>
                <br>
                @endforeach
                
            @else
                <p class="text-center">You have no quizzes created</p>
            @endif
    </div>
    {{-- body content on the test view --}}
    <div class="col-md-8 exam-questions">
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
        <h2>Quizzes</h2>
        <div class="exam-top-buttons"> 
            @can('test_create')   
            <a href="{{ url('admin/tests/create') }}"><button style="background: #060646"><i class="fa fa-plus"></i>Create</button></a>  
            @endcan 
            @if(empty($questions_array))
            {{-- //do nothing  $questions_array->isEmpty() ||  --}}
            @else                      
                <a href="{{ url('/admin/tests/attempts/'.$questions_array[0]->test->id)}}"><button style="background: #FD6C03"><i class="fa fa-check"></i>Submited</button></a>
                <a href="{{ url('/admin/tests/delete/'.$questions_array[0]->test->id) }}"><button style="background: #71CA52"><i class="fa fa-trash"></i>Delete</button></a>
            
            @endif
        </div>
        <h3>{{$course_title or 'Select Course'}}</h3>
        @if(count($my_questions) > 0)
            @foreach($my_questions as $key=>$question)
                <div class="disp-exams">
                    <p>{{++$key}}.{{$question->question }}</p>
                    {{-- <button style="background: #FD6C03">Edit</button> --}}
                    <a href="{{url('/admin/tests/delete-question/'.$question->id)}}" class="btn" style="background: #71CA52">
                        Remove
                    </a>
                </div>
            @endforeach
        @else
        <p class="text-center">No questions available</p>
        @endif
    </div>
</div>



    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        {{-- <div class="panel-body table-responsive">
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
    </div> --}}
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
                    <form method="post" action="/admin/tests">{{ csrf_field() }}
                        <input type="hidden" name="type" value="title"/>
                        <div class="col-xs-12 form-group">
                            <div class="form-group">
                                <h3>Select Course</h3>
                                <select name="course_id" class="form-control" id="">
                                    <option value="0">Select Course</option>
                                    @foreach($my_courses as $course)
                                        <option value="{{$course->course->id}}">{{$course->course->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-warning">Submit</button>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
          </div>
          
        </div>
      </div>
@endsection