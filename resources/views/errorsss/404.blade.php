@extends('layouts.qxphome')

@section('main')
<div class="container text-center error-404">
    <img src="{{asset('images/errors/error.png')}}" alt="">
        <h3 style="">Ooops!!</h3>
        <h4>Sorry, Something went wrong</h4>
          <p>We appologize for any inconvenieces caused. If the problem persists, please feel free to contact us.</p>
        <hr>
        <a href="/"><button>Go Back</button></a>
    </div>
@endsection