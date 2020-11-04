@extends('layouts.app')

@section('content')
  <div class="row">
    @include('students.header')
  </div>

    <div class="row" style="">
      <div class="row">
          <div class="col-md-8 course-top">
              <h3>Assignments</h3>
          </div>
          <div class="col-md-4 course-top">
            <a href="{{ url('admin/assignments/create') }}"><button><i class="fa fa-plus"></i> Add New</button></a>
          </div>
      </div>
      {{-- courses list --}}
      <div class="col-md-8">
        @if(count($my_assignments) > 0)
          @foreach($my_assignments as $assignment)
            <div class="assignment-card">
            <div class="col-sm-3 col-md-2 text-center" style="">
              <img src="https://placehold.it/70" style="padding-top:3px;"/>
            </div>
            <div class="assignment-text col-sm-8 col-md-8" style="">
              <p style="font-size: 1.3em;">{{ $assignment->title}}</p>
              <p style="font-size: .7em;">March 4 2020</p>
            <p style="font-size: .8em;color:grey;">{{ $assignment->course->title}}</p>
            </div>
            <div class="col-sm-1 col-md-2 text-center" style="">
              <a href="{{ url('admin/assignments/update/'.$assignment->id) }}" class="btn btn-info assignment-link" style="">More</a>
            </div>
            </div>
          @endforeach
        @else
        <p class="text-center">You have no upcoming assignments</p>
        @endif
      </div>
      {{-- right side --}}
      <div class="col-md-4">
          @include('admin.recents')
      </div>
  </div>




    <div class="panel panel-default" style="margin-top: 300px;">
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

