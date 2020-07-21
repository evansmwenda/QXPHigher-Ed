@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                    <div class="panel-group" id="accordion">

                        @if(count($exams) >0)
                          @foreach($exams as $exam)
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h4 class="panel-title">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $assignment->id }}">
                                  {{ $assignment->course->title}} - {{ $assignment->title}}</a>
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
                        @else
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <p>You don't have any exams</p>
                            </h4>
                          </div>
                        @endif

                        
                      </div>
                      <!-- radio -->
                      <!-- <div class="form-group">
                      	<p>1. Which one of the following animals is known for strength?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Elephant</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Hippopotamus</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Buffalo</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Shark</label>
                        </div>
                      </div>

                      <div class="form-group">
                      	<p>2. Which one of the following animals is known for speed?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Bear</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Cheetah</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Leopard</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Eagle</label>
                        </div>
                      </div>

                      <div class="form-group">
                      	<p>3. Which one of the following animals is known for faithfulness?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Antelope</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Dog</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Rabbit</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Elephant</label>
                        </div>
                      </div> -->
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection