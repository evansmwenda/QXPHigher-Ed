@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">In Progress<span style="font-size: .8em;color: grey;"><br>Your recent courses</span></div>

                <div class="panel-body">
                    <div class="col-sm-9">
                        <table class="table">
                          <tbody>
                            

                                <tr>
                                  <td>
                                    <p>View All Cou</p>
                                    <div class="progress progress-xs">
                                      <div class="progress-bar progress-bar-primary" role="progressbar"
                                           aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                        <span class="sr-only">40% Complete (success)</span>
                                      </div>
                                    </div>
                                  </td>
                                  <td><span class="badge bg-danger">40%</span></td>
                                </tr>
                            



                            <tr>
                              <td>
                                <p>Angular in steps</p>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-success" role="progressbar"
                                       aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                    <span class="sr-only">20% Complete</span>
                                  </div>
                                </div>
                              </td>
                              <td><span class="badge bg-danger">20%</span></td>
                            </tr>
                            <tr>
                              <td>
                                <p>ES6 foundation</p>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-warning" role="progressbar"
                                       aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">60% Complete (warning)</span>
                                  </div>
                                </div>
                              </td>
                              <td><span class="badge bg-danger">20%</span></td>
                            </tr>
                            <tr>
                              <td>
                                <p>Biology 101</p>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-danger " role="progressbar"
                                       aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                    <span class="sr-only">60% Complete (warning)</span>
                                  </div>
                                </div>
                              </td>
                              <td><span class="badge bg-danger">60%</span></td>
                            </tr>
                          </tbody>
                        </table>
                        <div style="text-align: center">
                            <a href="/courses">View All Courses</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">My Quizzes<span style="font-size: .8em;color: grey;"><br>Skill tests</span></div>

                <div class="panel-body">
                    <div class="col-sm-12">
                        <table class="table">
                          <tbody>

                                @foreach($test_details as $test)
                                    <tr>
                                        <td>
                                            <p style="margin-bottom: 0px !important">{{ $test->title }}</p>
                                            <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{ $test->name }}</span>
                                        </td>
                                        <td><span class="badge progress-bar-warning" style="float: right;">Good {{ $result_array[$test->test_id]}}</span></td>
                                    </tr>

                                @endforeach

                            <!-- <tr>
                              <td>
                                <p style="margin-bottom: 0px !important">Level 2 Angular</p>
                                <span style="font-size: .8em;color: grey;padding-top: 0px !important;">Angular in steps</span>
                              </td>
                              <td><span class="badge progress-bar-success" style="float: right;">Execellent 9.8</span></td>
                            </tr>
                            <tr>
                              <td>
                                <p style="margin-bottom: 0px !important">Graduation</p>
                                <span style="font-size: .8em;color: grey;padding-top: 0px !important;">Bootstrap Foundation</span>
                              </td>
                              <td><span class="badge progress-bar-danger" style="float: right;">Failed 2.8</span></td>
                            </tr> -->


                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">Recent Activity</div>

                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Task</th>
                          <th>Progress</th>
                          <th style="width: 40px">Label</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1.</td>
                          <td>Update software</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                          </td>
                          <td><span class="badge bg-danger">55%</span></td>
                        </tr>
                        <tr>
                          <td>2.</td>
                          <td>Clean database</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar bg-warning" style="width: 70%"></div>
                            </div>
                          </td>
                          <td><span class="badge bg-warning">70%</span></td>
                        </tr>
                        <tr>
                          <td>3.</td>
                          <td>Cron job running</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar bg-primary" style="width: 30%"></div>
                            </div>
                          </td>
                          <td><span class="badge bg-primary">30%</span></td>
                        </tr>
                        <tr>
                          <td>4.</td>
                          <td>Fix and squish bugs</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar bg-success" style="width: 90%"></div>
                            </div>
                          </td>
                          <td><span class="badge bg-success">90%</span></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-decoration: bold;color: #000;">Skills</div>

                <div class="panel-body">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 40px">#</th>
                          <th>Progress</th>
                          <th style="width: 10px">Label</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>HTML5</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                          </td>
                          <td><span class="badge progress-bar-danger">55%</span></td>
                        </tr>
                        <tr>
                          <td>SCC/SCSS</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar progress-bar-warning" style="width: 70%"></div>
                            </div>
                          </td>
                          <td><span class="badge progress-bar-warning">70%</span></td>
                        </tr>
                        <tr>
                          <td>JAVASCRIPT</td>
                          <td>
                            <div class="progress progress-xs ">
                              <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                            </div>
                          </td>
                          <td><span class="badge progress-bar-primary">30%</span></td>
                        </tr>
                        <tr>
                          <td>RUBY</td>
                          <td>
                            <div class="progress progress-xs ">
                              <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                            </div>
                          </td>
                          <td><span class="badge progress-bar-success">90%</span></td>
                        </tr>
                        <tr>
                          <td>VUE.JS</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar progress-bar-warning" style="width: 90%"></div>
                            </div>
                          </td>
                          <td><span class="badge progress-bar-warning">90%</span></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection