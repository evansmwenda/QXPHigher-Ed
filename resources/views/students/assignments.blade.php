@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Assignments</div>

                <div class="panel-body">

                	<div class="col-sm-10">
                      <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                              Biology 101</a>
                            </h4>
                          </div>
                          <div id="collapse1" class="panel-collapse collapse in">
                            <div class="panel-body">Assignment on research of the different methods of gathering scientific data<br>
                            <a href="test_file.zip" download>Download File</a>
                            <p style="padding-top: 20px;">Once completed, you can submit the assignment from the section below</p>
                            <form role="form">
                               <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="">Upload</span>
                                  </div>
                                </div>
                              </div>

                              <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                          </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                              English 101</a>
                            </h4>
                          </div>
                          <div id="collapse2" class="panel-collapse collapse">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                            minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                            commodo consequat.</div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                              Mathematics Lv 1</a>
                            </h4>
                          </div>
                          <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                            minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                            commodo consequat.</div>
                          </div>
                        </div>
                      </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection
