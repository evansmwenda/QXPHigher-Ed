@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
    <div class="row account-bg">
        <div class="qxp-overlay-education">
            <div class="container">
                <div class="row">
                    <div class="spacer">
                        <div class="col-sm-12 col-md-5">
                            <div class="account-card">
                                <div class="account-img text-center" >
                                    <i class="fas fa-user"></i>
                                    {{-- <img src="https://via.placeholder.com/100" alt="logo" height="100px" width="100px"> --}}
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
                        <div class="col-sm-12 col-md-6">
                                    @if(session('success'))
                                    <!-- If password successfully show message -->
                                    <div class="row">
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                @else
                                    {!! Form::open(['method' => 'PATCH', 'route' => ['auth.change_password']]) !!}
                                    <!-- If no success message in flash session show change password form  -->
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            @lang('global.app_edit')
                                        </div>

                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-xs-12 form-group">
                                                    {!! Form::label('current_password', 'Current password*', ['class' => 'control-label']) !!}
                                                    {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                                                    <p class="help-block"></p>
                                                    @if($errors->has('current_password'))
                                                        <p class="help-block">
                                                            {{ $errors->first('current_password') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 form-group">
                                                    {!! Form::label('new_password', 'New password*', ['class' => 'control-label']) !!}
                                                    {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => '']) !!}
                                                    <p class="help-block"></p>
                                                    @if($errors->has('new_password'))
                                                        <p class="help-block">
                                                            {{ $errors->first('new_password') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 form-group">
                                                    {!! Form::label('new_password_confirmation', 'New password confirmation*', ['class' => 'control-label']) !!}
                                                    {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => '']) !!}
                                                    <p class="help-block"></p>
                                                    @if($errors->has('new_password_confirmation'))
                                                        <p class="help-block">
                                                            {{ $errors->first('new_password_confirmation') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </div>


                                @endif
                        </div>
                    </div>
                </div>
            </div>
                    
        </div>
    </div>

@endsection

