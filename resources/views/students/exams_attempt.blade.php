@extends('layouts.home')

@section('main')
    <div class="row">
        @include('students.header')
        <div class="col-md-12">
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

          <div class="row">
           <h2>Exams Attempt</h2>
          </div>


        </div>
    </div>
@endsection