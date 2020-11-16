@extends('layouts.qxphome')

@section('main')
<div class="container" style="margin: 20px auto;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(Session::has("flash_message_error")) 
    <div class="alert alert-error alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>{!! session('flash_message_error') !!}</strong>
    </div> 
  @endif 

    @if(Session::has("flash_message_success")) 
    <div class="alert progress-bar-success alert-block">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong style="color:#11BECC;">{!! session('flash_message_success') !!}</strong>
    </div> 
    @endif
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    Before proceeding, please check your email for a verification link.
                   If you did not receive the email, click the below button
                    <form class="d-inline" method="POST" action="/register/activate">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
                        <div class="form-group row">
                            <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            
                                <input id="email" type="email" readonly class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email }}" required autocomplete="email">

                                <br>
                           
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-primary" style="z-index: 0;">
                                {{ __('click here to request another') }}
                            </button>
                        </div>
                    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection