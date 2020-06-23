@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.tests.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
            	<form>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Email address</label>
                    	<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
	                </div>
                </form>
            </div>
            
        </div>
    </div>

@endsection    

