@extends('layouts.home')

@section('main')
<div class="row account-bg">
    <div class="qxp-overlay-education">
        @if(Session::has("flash_message_error")) 
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div> 
          @endif 

        @if(Session::has("flash_message_success")) 
            <div class="alert progress-bar-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong style="color:white;">{!! session('flash_message_success') !!}</strong>
            </div> 
        @endif
        <div class="container">
            <div class="row">
                <div class="spacer">
                    <div class="col-sm-12 col-md-5">
                        <div class="account-card">
                            <div class="account-img text-center" >
                                <img src="https://via.placeholder.com/100" alt="logo" height="100px" width="100px">
                            </div>
                            <div class="account-text">
                                <h3>Let's get you set up</h3>
                                <p>
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                                    Sequi, veritatis. Hic expedita provident atque perferendis pariatur, 
                                    dolorum veniam, error voluptatum nesciunt nostrum corrupti ullam totam, 
                                    dolor fuga aut possimus obcaecati.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="spacer">
                    <div class="col-sm-12 col-md-7">
                        <form style="padding-top:70px;" action="{{url('account')}}" method="POST">
                            <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">
                            <div class="form-group row" >
                              <label for="inputName3" class="col-sm-2 col-form-label" style="color: #fff">Name</label>
                              <div class="col-sm-7">
                              <input type="text" class="form-control" id="inputName3" name="username" placeholder="Name" value="{{$user->name or ''}}">
                              </div>
                            </div>
                            <div class="form-group row" style="padding-top: 20px;">
                                <label for="inputSchool3" class="col-sm-2 col-form-label" style="color: #fff">School</label>
                                <div class="col-sm-7">
                                  <input type="text" class="form-control" id="inputSchool3" name="school_name" placeholder="School" value="{{$user->school->name or ''}}">
                                </div>
                              </div>
                            <div class="form-group row" style="padding-top: 20px;">
                                <label for="inputSchID3" class="col-sm-2 col-form-label" style="color: #fff">School ID</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="inputSchID3" name="school_id" placeholder="School ID" value="{{$user->school->id or ''}}">
                                </div>
                            </div>
                            <div class="form-group row" style="padding-top: 20px;">
                                <label for="inputEmail3" class="col-sm-2 col-form-label" style="color: #fff">Email</label>
                                <div class="col-sm-7">
                                    <input type="email" class="form-control" id="inputEmail3" name="email" placeholder="Email" value="{{$user->email or ''}}">
                                </div>
                            </div>  

                            <div class="form-group row text-center" style="padding-top: 20px;">
                              <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">SAVE</button>
                              </div>
                            </div>
                          </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>

@endsection