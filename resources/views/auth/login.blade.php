@extends('layouts.auth')

{{-- @section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ ucfirst(config('app.name')) }} Lasfasfasogin</div>
                <div class="panel-body">
                    
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were problems with input:
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal"
                          role="form"
                          method="POST"
                          action="{{ url('login') }}">
                        <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password"
                                       class="form-control"
                                       name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('auth.password.reset') }}">Forgot your password?</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="/register">Don't have an account? Sign Up</a>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <label>
                                    <input type="checkbox"
                                           name="remember"> Remember me
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit"
                                        class="btn btn-primary"
                                        style="margin-right: 15px;">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3 custom-login">
       <div class="col-md-6 col-sm-6 log-in">
        <img class="qxp-logo" src="{{asset('images/logo/logo.svg')}}" width="100" height="100">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were problems with input:
            <br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
          {{-- login form --}}
          <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <input type="email" class="form-control" placeholder="Username" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password">
            </div>
            <div class="form-group">  
                <button type="submit" class="btn btn-warning" style="margin-right: 15px;">
                    Login
                </button>
            </div>
            <div class="form-group">
                <a href="{{ route('auth.password.reset') }}">Forgot your password?</a>
            </div>
            <div class="form-group">
                    <a href="/register">Sign Up With</a>
            </div>
            <div class="row">
                <span class="fab fa-twitter fa-2x"></span>
                <span class="fab fa-facebook fa-2x"></span>
                <span class="fab fa-google-plus-g fa-2x"></span>
            </div>
          </form>

       </div>
        <div class="col-md-6 col-sm-6 log-side">
        <h3>Connecting People Together</h3>
        <p>For meeting and learning online with teleconference,
            video conference, remote working, work from home
            and work from anywhere</p>
        </div>
       </div>
    </div>
</div>
@endsection