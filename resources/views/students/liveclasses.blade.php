@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-8">
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
                <div class="panel-heading">JOIN LIVE CLASS</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                		<form class="form-horizontal"
                          role="form"
                          method="POST"
                          action="/user/dojoin">
	                        <input type="hidden"
	                               name="_token"
	                               value="{{ csrf_token() }}">

	                        <div class="form-group">
	                            <label class="col-md-4 control-label">Class ID</label>

	                            <div class="col-md-6">
	                                <input type="text"
	                                       class="form-control"
	                                       name="meetingID"
	                                       placeholder="Enter Class ID to join" 
	                                       required>
	                            </div>
	                        </div>


	                        <div class="form-group">
	                            <div class="col-md-6 col-md-offset-4">
	                                <button type="submit"
	                                        class="btn btn-primary"
	                                        style="margin-right: 15px;">
	                                    JOIN LIVE CLASS
	                                </button>
	                            </div>
	                        </div>
	                    </form>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection
