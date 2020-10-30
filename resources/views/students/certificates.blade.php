@extends('layouts.home')

@section('main')
    <div class="row">
        @include('students.header')
    </div>
    {{-- content  --}}
    <div class="row">
        <div class="row exam-top">
            <div class="exam-overlay">
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
                <h2>Certification</h2>
                
                <span><strong>Well Deserved</strong></span>
            </div>
          </div>
    </div>
    <div class="row certification">
        <h3>Awarded Certificates</h3>
       
            <div class="row">
                  <div class="cert-card">
                        <div class="cert-header">
                        Fine and Arts
                        </div>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                        <span class="fa fa-star"></span>
                        <br><br>
                        <i>Author:</i><br>
                        <i>Dr, Harry Garza</i>
                  </div>
                  <div class="cert-card">
                    <div class="cert-header">
                    Fine and Arts
                    </div>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <br><br>
                    <i>Author:</i><br>
                    <i>Dr, Harry Garza</i>
              </div>
              <div class="cert-card">
                <div class="cert-header">
                Fine and Arts
                </div>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <br><br>
                <i>Author:</i><br>
                <i>Dr, Harry Garza</i>
                </div>
                <div class="cert-card">
                    <div class="cert-header">
                    Fine and Arts
                    </div>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <br><br>
                    <i>Author:</i><br>
                    <i>Dr, Harry Garza</i>
                </div>
            </div>
            <div class="exam-warning col-md-11">
                <div class="row">
                  <div class="col-md-2 exam-fa text-center">
                    <span class="fa fa-user fa-2x"></span>
                  </div>
                  <div class="col-md-9 exam2">
                    <h3>Congratulations!</h3>
                    <i>All certificates are awarded upon marking completion.</i>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="cert-card">
                      <div class="cert-header">
                      Fine and Arts
                      </div>
                      <span class="fa fa-star"></span>
                      <span class="fa fa-star"></span>
                      <span class="fa fa-star"></span>
                      <span class="fa fa-star"></span>
                      <br><br>
                      <i>Author:</i><br>
                      <i>Dr, Harry Garza</i>
                </div>
                <div class="cert-card">
                  <div class="cert-header">
                  Fine and Arts
                  </div>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <br><br>
                  <i>Author:</i><br>
                  <i>Dr, Harry Garza</i>
            </div>
            <div class="cert-card">
              <div class="cert-header">
              Fine and Arts
              </div>
              <span class="fa fa-star"></span>
              <span class="fa fa-star"></span>
              <span class="fa fa-star"></span>
              <span class="fa fa-star"></span>
              <br><br>
              <i>Author:</i><br>
              <i>Dr, Harry Garza</i>
              </div>
              <div class="cert-card">
                  <div class="cert-header">
                  Fine and Arts
                  </div>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <span class="fa fa-star"></span>
                  <br><br>
                  <i>Author:</i><br>
                  <i>Dr, Harry Garza</i>
              </div>
          </div>

          pagination here
    </div>
@endsection