@extends('layouts.app')

@section('content')
  <div class="row">
    @include('students.header')
  </div>

    <div class="row" style="">
      <div class="row">
          <div class="col-md-8 course-top">
              <h3>Enrollments</h3>
          </div>
          <div class="col-md-4 course-top">
            <a href="{{ url('admin/enrollments/create') }}"><button><i class="fa fa-plus"></i> Add New</button></a>
          </div>
      </div>
  </div>

@endsection    

