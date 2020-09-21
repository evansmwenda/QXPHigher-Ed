@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">In Progress<span style="font-size: .8em;color: grey;"><br>Your recent courses</span></div>

                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table">
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
                        <div style="text-align: center">
                            <a href="/courses">View All Courses</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Events<span style="font-size: .8em;color: grey;"><br>Live classes,other events</span></div>

                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table">
                          <tbody>
                            @foreach($monthly as $key=>$event)
                              <tr>
                                  <td>
                                      <p style="margin-bottom: 0px !important">{{$event->title}} </p>
                                      <p style="margin-bottom: 0px !important">When: <b>{{ $event->event_start_time }}</b></p>
                                      <p style="margin-bottom: 0px !important">Meeting ID: <b>Mhdfj4</b></p>
                                      <a style="margin-bottom: 0px !important" href="https:/qxpacademy.com/user/live/Mhdfj4">https:/qxpacademy.com/user/live/Mhdfj4</a><br/>
                                      <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{$event->course_title}}</span>
                                  </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Quizzes<span style="font-size: .8em;color: grey;"><br>CATs,exams,tests</span></div>

                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Quiz</th>
                          <th>Score</th>
                        </tr>
                      </thead>
                      <tbody>
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
                      </tbody>
                    </table>
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
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection