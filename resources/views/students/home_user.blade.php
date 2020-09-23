@extends('layouts.home')

@section('main')
{{-- At glance boxes --}}
    <div class="col-sm-12">
      <div class="col-md-3 qxp-primary qxp-card">
        <div class="card">
          <div class="card-header pull-left">
            <i class="glyphicon glyphicon-book fa-3x"></i>
          </div>
          <div class="card-body">
            <h5 class="qxp-light">ENROLLED COURSES</h5>
            <p class="card-text" style="font-size: 25px; font-style:bold">12</p>     
          </div>
        </div>
      </div>
      <div class="col-md-3 qxp-secondary qxp-card">
        <div class="card">
          <div class="card-header pull-left">
            <i class="glyphicon glyphicon-calendar fa-3x"></i>
          </div>
          <div class="card-body">
            <h5 class="card-title">SCHEDULED EVENTS</h5>
            <p class="card-text" style="font-size: 25px; font-style:bold">12</p>   
          </div>
        </div>
      </div>
      <div class="col-md-3 qxp-lightprimary qxp-card"">
        <div class="card">
          <div class="card-header pull-left">
            <i class="glyphicon glyphicon-list fa-3x"></i>
          </div>
          <div class="card-body">
            <h5 class="card-title">EXAMS</h5>
            <p class="card-text" style="font-size: 25px; font-style:bold">12</p>  
      
          </div>
        </div>
      </div>
      <div class="col-md-2 qxp-lightsecondary qxp-card">
        <div class="card">
          <div class="card-header pull-left">
            <i class="glyphicon glyphicon-tasks fa-3x"></i>
          </div>
          <div class="card-body">
            <h5 class="card-title">QUIZES</h5>
            <p class="card-text" style="font-size: 25px; font-style:bold">12</p>           
          </div>
        </div>
      </div>
    </div>
    {{-- end of glance boxes --}}
    
    <div class="row">
      <div class="clear_fix"></div>
        <div class="col-md-7">
            <div class="panel panel-info">
                <div class="panel-heading" style="text-decoration: bold;color: #000080;">Course Progress<span style="font-size: .8em;color: grey;"><br>Your recent courses</span></div>

                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                          <tbody>
                            @foreach($enrolled_course as $key => $course)

                                <tr>
                                  <td>
                                    <p>{{ $course->title}}</p>
                                    <div class="{{ $prog_parent[$key] }}">
                                      <div class="{{ $progress_array[$key] }}" role="progressbar"
                                           aria-valuenow="{{ $course_progress[$key]}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $course_progress[$key]}}%">
                                        <span class="sr-only">{{ $course_progress[$key]}}% Complete (success)</span>
                                      </div>
                                    </div>
                                  </td>
                                  <td><span class="{{ $badge_array[$key] }}">{{ $course_progress[$key]}}%</span></td>
                                </tr>

                            @endforeach
                          </tbody>
                        </table>
                        {{-- paginate the table --}}

                        <div style="text-align: center">
                            <a href="/courses">View All Courses</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Events<span style="font-size: .8em;color: grey;"><br>Live classes,other events</span></div>

                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table">
                          <thead class="bg-info">
                            <tr>
                              <th>#</th>
                              <th>Meeting Group</th>
                              <th>Scheduled Date</th>
                              <th>Meeting Link</th>
                            </tr>
                          </thead>
                          <tbody>
                           
                            @if(count($monthly)<=0)
                              <p style="text-align: center">You have no events</p> 
                            @else

                              @foreach($monthly as $key=>$event)
                              
                                <tr>
                                    <td>#</td>
                                      <td>
                                        <p style="margin-bottom: 0px !important">{{$event->title}} </p>
                                        <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{$event->course_title}}</span>
                                      </td>

                                      <td>{{ $event->event_start_time }}</td>
                                   
                                     <td><a href="https:/qxpacademy.com/user/live/Mhdfj4">https:/qxpacademy.com/user/live/Mhdfj4</a></td>
                                        
                                    </td>
                                </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                        <div style="text-align: center">
                            <a href="/calender">View All Events</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-success"">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Quizzes<span style="font-size: .8em;color: grey;"><br>CATs,exams,tests</span></div>

                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Quiz</th>
                          <th>Score</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($test_details)<=0)
                          <p style="text-align: center">You have no Quizzes</p> 
                        @else
                          @foreach($test_details as $key=>$test)
                              <tr>
                                <td>{{++$key}}</td>
                                  <td>
                                      <p style="margin-bottom: 0px !important">{{ $test->title }}</p>
                                      <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{ $test->name }}</span>
                                  </td>
                                  <td><span class="badge progress-bar-info" style="float: right;padding-left:10px;padding-right:10px;padding-top:4px;padding-bottom:4px;">{{ $result_array[$test->test_id]}}</span></td>
                              </tr>
                          @endforeach
                        @endif
                      </tbody>
                    </table>
                    {{-- Paginate the table --}}
                      <div style="text-align: center">
                            <a href="/exams">View All Quizzes</a>
                      </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Assignments</div>

                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 40px">#</th>
                          <th>Name</th>
                          <th>Due date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($assignments)<=0)
                          <p style="text-align: center">You have no assignments</p> 
                        @else
                          @foreach($assignments as $key=>$assignment)
                            <tr>
                              <td>{{++$key}}</td>
                              <td>
                                <p style="margin-bottom: 0px !important">{{$assignment['title']}}</p>
                                <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{$assignment->course->title}}</span>
                              </td>
                              <td>
                                <p>12-08-2019</p> 
                              </td>
                            </tr>
                          @endforeach
                        @endif  
                      </tbody>
                    </table>
                    <div style="text-align: center">
                            <a href="/assignments">View All Assignments</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection