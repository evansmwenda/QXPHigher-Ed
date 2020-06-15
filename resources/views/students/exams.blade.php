@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                	<div class="col-sm-6">
                      <!-- radio -->
                      <div class="form-group">
                      	<p>1. Which one of the following animals is known for strength?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Elephant</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Hippopotamus</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Buffalo</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Shark</label>
                        </div>
                      </div>

                      <div class="form-group">
                      	<p>2. Which one of the following animals is known for speed?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Bear</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Cheetah</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Leopard</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio2">
                          <label class="form-check-label">Eagle</label>
                        </div>
                      </div>

                      <div class="form-group">
                      	<p>3. Which one of the following animals is known for faithfulness?</p>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Antelope</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Dog</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Rabbit</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio3">
                          <label class="form-check-label">Elephant</label>
                        </div>
                      </div>
                    </div>


                    
                </div>
            </div>
        </div>
    </div>
@endsection