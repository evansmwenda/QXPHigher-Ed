@extends('layouts.qxphome')

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

@section('main')
<div class="row qxp-login">
    <div class="qxp-overlay qxp-overlay-education">
        <div class="container " style="height: 200px;">
            <div class="row">
                <div class="col-5">
                    <div class="spacer">
                        <h1>Connecting People Together</h1>
                      <p>For meeting and working online with teleconferencing, video conference, remote working, work from home and work from anywhere</p> 
                    </div>
                </div>
                <div class="col-4">
                    <div class="custom-login">
                        <div class="text-center">
                            <img class="text-center" src="{{asset('images/logo/logo.svg')}}" width="100" height="100">
                        </div>
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
                               
                                    <label>
                                        <input type="checkbox" name="remember"> Remember me
                                    </label>

                            </div>
                            <div class="form-group">  
                                <button type="submit" class="btn btn-warning">
                                    Login
                                </button>
                            </div>
                            <div class="form-group">
                                <a href="{{ route('auth.password.reset') }}">Forgot your password?</a>
                            </div>
                            <div class="form-group">
                                    <a href="/register">Sign Up</a>
                            </div>
                          </form>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection