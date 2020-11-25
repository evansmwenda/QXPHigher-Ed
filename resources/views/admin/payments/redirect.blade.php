@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-12">

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

                  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <b>PAYMENT STATUS</b>
                    <blockquote>
                        <b>Merchant reference: </b> <?php echo $reference; ?><br />
                      <b>Pesapal Tracking ID: </b> <?php echo $tracking_id; ?><br />
                      <b>Status: </b> <?php echo $status; ?><br />
                    </blockquote>
                  </div>
                  
                  <div id="rightcol2" class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <p style="font-size: 16px">Your payment is being processed. We will notify you once it is completed.</p>
                    <hr style="color: #C0C0C0">
                    <div style="float: right;margin-right: 30px;">
                      <button class="btn btn-primary whited"
                      onclick="window.parent.location = window.parent.location.href">Check Status</button>
                    <a style="margin-left:15px;text-decoration:none;color:#fff;padding:5px 10px;" href="/admin/home" class="btn btn-primary whited" role="button" aria-pressed="true">Goto Homepage</a>
                    </div><br/><br/><br/>
                  </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection
