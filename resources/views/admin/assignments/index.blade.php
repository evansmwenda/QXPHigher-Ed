@extends('layouts.app')

@section('content')
    <h3 class="page-title">Assignments</h3>
    <p>
        <a href="{{ url('/admin/assignments/create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>
    
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

    <div class="panel panel-default">
        <div class="panel-heading">
            View
        </div>
        
        <div class="panel-body table-responsive">
            <div class="row">
                <div class="panel-group" id="accordion">

                    @if(count($my_assignments) >0)
                      @foreach($my_assignments as $assignment)
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $assignment->id }}">
                              {{ $assignment->course->title}} - {{ $assignment->title}}</a>
                            </h4>
                          </div>
                          <div id="collapse{{ $assignment->id }}" class="panel-collapse collapse">
                            <div class="panel-body">
                              @if(count($submitted_assignments_array[$assignment->id]) > 0 )
                                <table class="table table-bordered table-striped {{ count($submitted_assignments_array) > 0 ? 'datatable' : '' }}">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Student Name</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @if (count($submitted_assignments_array[$assignment->id]) > 0)
                                            @foreach($submitted_assignments_array[$assignment->id] as $submitted_assignment)
                                                <tr data-entry-id="{{ $submitted_assignment->id }}">
                                                    <td>{{ $submitted_assignment->id }}</td>
                                                    <td>{{ $submitted_assignment->user->name }}</td>
                                                    <td>
                                                      <a href="{{url('uploads/assignments/'.$assignment->course->slug.'/'.$submitted_assignment->filename)}}" download>Download File</a></td>
                                                </tr>
                                            @endforeach
                                            
                                        @else
                                            <tr>
                                                <td colspan="10">No students have submitted</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                              @else
                                <p>No student has submitted their assignment</p>  
                              @endif  
                              
                          </div>
                          </div>
                        </div>
                      @endforeach
                    @else
                      <div class="panel-heading">
                        <h4 class="panel-title">
                          <p>You have not created any assignments</p>
                        </h4>
                      </div>
                    @endif

                    
                  </div>
            </div>
        </div>
    </div>

@endsection    

