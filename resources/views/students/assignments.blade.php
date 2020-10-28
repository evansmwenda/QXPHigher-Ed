@extends('layouts.home')

@section('main')
<div class="col-md-8" >
	<div class="row top-header-2">

		<div class="col-md-12 col-sm-12" >
			<div class="col-sm-6">
				<div class="form-group has-search">
					<input type="text" class="form-control" placeholder="Search">
				  </div>
			</div>
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

  {{-- @foreach($assignments as $assignment)
  <tr>
    <td>{{ $assignment->id }}</td>
   <td>{{ $assignment->course->title}}</td>
   <td>{{ $assignment->title}}</td>
   <td>Due Date</td>
   <td>
     <a class="btn btn-primary btn-sm" href="{{ $assignment->id }}">
       <i class="fas fa-folder">
       </i>
       View
   </a>
   </td>
  </tr>
  @endforeach --}}

  @if(count($assignments) > 0)
    @foreach($assignments as $assignment)
      <div class="assignment-card">
        <div class="col-sm-3 col-md-2 text-center" style="">
          <img src="https://placehold.it/80" style="padding-top:3px;"/>
        </div>
        <div class="assignment-text col-sm-8 col-md-8" style="">
          <p style="font-size: 1.3em;">{{ $assignment->title}}</p>
          <p style="font-size: 1em;">Feb 4 2020 - March 4 2020</p>
        <p style="font-size: .8em;color:grey">{{ $assignment->course->title}}</p>
        </div>
        <div class="col-sm-1 col-md-2 text-center" style="">
          <a href="{{ $assignment->id }}" class="btn btn-info assignment-link" style="">Open</a>
        </div>
      </div>
    @endforeach
  @else
    {{-- //no assignments --}}
    <p class="text-center">You have no upcoming assignments</p>
  @endif


  
  

	</div>
	{{-- right side --}}
	<div class="col-md-4 dashboard-right">
		<div class="row top-right">
			<i class="fa fa-user fa-2x"></i> 
				<a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
				   <span class="sr-only">Toggle navigation</span>
				   <span class="fa fa-bars"></span>
			   </a> 

	   </div>
	   {{-- @include('partials.recentactivity') --}}
  </div>  
  




    {{-- <div class="row">
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
                <div class="panel-heading">My Assignments </div>

                <div class="panel-body">

                	<div class="col-sm-12">
                      <div class="panel-group" id="accordion">

                        @if(count($assignments) >0)
                         
                           <table class="table table-bordered table-striped">
                             <tr>
                               <thead class="bg-info">
                                 <td>#</td>
                                 <td>Course Name</td>
                                 <td>Assignment Title</td>
                                 <td>Date Due</td>
                                 <td>Action</td>
                               </thead>
                             </tr>
                             <tbody>
                              @foreach($assignments as $assignment)
                               <tr>
                                 <td>{{ $assignment->id }}</td>
                                <td>{{ $assignment->course->title}}</td>
                                <td>{{ $assignment->title}}</td>
                                <td>Due Date</td>
                                <td>
                                  <a class="btn btn-primary btn-sm" href="{{ $assignment->id }}">
                                    <i class="fas fa-folder">
                                    </i>
                                    View
                                </a>
                                </td>
                               </tr>
                               @endforeach
                             </tbody>
                           </table>
                         
                        @else
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <p>You don't have any assignments</p>
                            </h4>
                          </div>
                        @endif

                        
                      </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div> --}}
@endsection
