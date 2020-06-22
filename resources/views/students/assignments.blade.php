@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-10">

          @if(Session::has("flash_message_error")) 
          <div class="alert alert-error alert-block">
              <button type="button" class="close" data-dismiss="alert">x</button>
              <strong>{!! session('flash_message_error') !!}</strong>
          </div> 
          @endif 

          @if(Session::has("flash_message_success")) 
              <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>{!! session('flash_message_success') !!}</strong>
              </div> 
          @endif


            <div class="panel panel-default">
                <div class="panel-heading">Assignments - {{ $method }}</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                      <div class="panel-group" id="accordion">

                        @foreach($assignments as $assignment)
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $assignment->id }}">
                              {{ $assignment->course->title}}</a>
                            </h4>
                          </div>
                          <div id="collapse{{ $assignment->id }}" class="panel-collapse collapse">
                            <div class="panel-body">
                              <h4>{{ $assignment->title }}</h4><br>
                              {{ $assignment->description }}<br>
                            <a href="{{url('uploads/assignments/'.$assignment->course->slug.'/'.$assignment->media)}}" download>Download File</a>
                            <p style="padding-top: 20px;">Once completed, you can submit the assignment from the section below</p>
                            <form role="form" enctype="multipart/form-data" method="post" action="{{('/assignments')}}"> {{csrf_field() }}
                               <div class="form-group">
                                <input type="hidden" id="assignment_id" name="assignment_id" value="{{ $assignment->id }}">
                                <input type="hidden" id="slug" name="slug" value="{{ $assignment->course->slug }}">
                                <label for="exampleInputFile">Choose Assignment</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" name="assignment" class="custom-file-input" id="assignment" required>
                                  </div>
                                </div>
                              </div>

                              <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                          </div>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection
