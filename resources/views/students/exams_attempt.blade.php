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
                <div class="panel-heading">{{ $test_details->title - $test_details->course->title }}</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                    <div class="panel-group" id="accordion">

                        @if(count($exams) >0)
                          <form role="form" method="post" action="{{('/exams/save/'.$id)}}"> {{csrf_field() }}
                           @foreach($exams as $key=>$exam)
                            <div class="form-group">
                              <p>{{ $key+1 }}. {{ $exam->question}}</p>
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
                           @endforeach 
                          </form>  

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