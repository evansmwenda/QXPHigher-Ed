@extends('layouts.app')

@section('content')
    <h3 class="page-title">Live Classes</h3>
    <p>
        <a href="{{ url('/admin/exams/create') }}" class="btn btn-success">Create</a>
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
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
        	<div class="row .d-none .d-xs-block .d-sm-block .d-md-none">
	            <div class="col-sm-12 col-md-12 text-center">
	                <button onclick="toggleCreate()" class="btn btn-lg" style="width:150px;background-color:#0734ff;color:white;text-align: center;"><span>Create Meeting</span></button>
	                <button onclick="toggleJoin()" class="btn btn-lg" style="width:150px;background-color:#0734ff;color:white;text-align: center;"><span>Join Meeting</span></button>
	                <p style="font-size: 2em;padding-top: 10px;color:#0734ff">&#0149;</p>


	            </div>
	          </div>
        </div>
    </div>
    
    @endsection    