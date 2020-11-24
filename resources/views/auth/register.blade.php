@extends('layouts.qxphome')
@section('main')
{{-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="redirect_url" value="{{ request('redirect_url', '/') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="meeting-login-design">
    <div class="login-pref login-meeting-overlay">
        
            
                <div class="meeting-spacer">
                    <h1><strong>Connecting People Together</strong></h1>
                  <p>For meeting and working online with teleconferencing, video conference, remote working, work from home and work from anywhere</p> 
                </div>
                <div class="meeting-custom-login">
                    <div class="text-center">
                        {{-- <img class="text-center" src="{{asset('images/logo/bgAsset8.svg')}}" width="100" height="100"> --}}
                    <h4 style="color: #060646">Sign Up for Free</h4>
                    <p>Get 14 days free trial</p>
                    </div>
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
                <form class="form-horizontal" role="form" method="POST" action="{{ url('register-user') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <select id="role_id" class="form-control @error('role_id') is-invalid @enderror" 
                     name="role_id" value="{{ old('role_id') }}"  required autocomplete="role_id" autofocus>
                       <option value="">Register as</option>
                       <option value="3">Student</option>
                       <option value="2">Teacher</option>
                     </select>
                   </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

                            <input id="name" placeholder="Full Names" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                       
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">   
                            <input id="email" placeholder="Email Address" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                       
                    </div>
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">   
                        <input id="phone" placeholder="Mobile Number" type="number" class="form-control" name="phone" required>

                        @if ($errors->has('Phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('Phone') }}</strong>
                            </span>
                        @endif
                   
                </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" placeholder="Password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        
                    </div>

                    <div class="form-group">
                            <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>
                    
                    </div>

                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary">
                                Register
                            </button>
                            <br>
                            <a href="{{ route('auth.login') }}">Existing user? Log in here</a>
                        </div>
                    </div>
                </form>
            
            </div>
    </div>
</div>
@endsection
