@extends('layouts.app')

@section('content')
<div class="col-md-8">
  <div class="row top-header-2-teacher">
      <div class="col-md-12 col-sm-12" >
          <div class="col-sm-6">
              <div class="form-group has-search">
                  <input type="text" class="form-control" placeholder="Search">
              </div>
          </div>
          <div class="col-md-6">
            <div class="col-sm-2">
                <span class="fa fa-shield-alt fa-2x"></span>
            </div>
            <div class="col-sm-2">
                <span class="fa fa-bell fa-2x"></span>
            </div>
            <div class="col-sm-2">
                <span class="fa fa-calendar-alt fa-2x"></span>
            </div>
          </div>

      </div> 
  </div>
</div>
<div class="col-md-4 dashboard-right">
  <div class="row top-right-teacher">
  <a href=""><i class="fa fa-user"></i>  {{\Auth::user()->name}}</a> 
          <a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="fa fa-bars"></span>
          </a> 
  </div>
</div>


{{-- error display goes here --}}
<div class="error-404">
    <img src="{{asset('images/errors/error.png')}}" alt="">
        <h3 style="">Ooops!!</h3>
        <h4>Sorry, Something went wrong</h4>
          <p>We appologize for any inconvenieces caused. If the problem persists, please feel free to contact us.</p>
        <hr>
        <a href="/"><button>Go Back</button></a>
</div>

@endsection