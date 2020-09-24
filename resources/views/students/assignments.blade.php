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
    </div>
@endsection
