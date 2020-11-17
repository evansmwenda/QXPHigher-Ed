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
                <div class="panel-heading">Subscribe</div>

                <div class="panel-body">

                  <div class="col-sm-10">
                    <div class="card" style="">
                      <!-- <img class="card-img-top" src="..." alt="Card image cap"> -->
                      <div class="card-body">
                        <h5 class="card-title">
                          Package: {{$subscription[0]->package->name}} 
                          @if($active)
                          <span style="color:green">(Active)</span>
                          @else
                          <span style="color:red">(Expired)</span>
                          @endif
                          
                          </h5>
                        <p class="card-text">
                          Expiry Date : 
                          @if($active)
                            <span style="color:green;">{{$expiry_on}}</span>
                          @else
                           <span style="color:red;">{{$expiry_on}}</span>
                          @endif  
                        </p>
                        <a href="{{url('/subscribe/'.$subscription[0]->package_id)}}" class="btn btn-primary">Renew Subscription</a>
                      </div>
                    </div>
                    

                    @if(isset($iframe_src))
                      <div class="col-xs-12 col-sm-12">
                          <iframe src="{{ $iframe_src }}" width="100%" height="700px" scrolling="no" frameBorder="0">
                            <p>Browser unable to load iFrame</p>
                          </iframe>
                      </div>
                    @endif

                  </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection
