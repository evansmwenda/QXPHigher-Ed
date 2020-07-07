@extends('layouts.app')

@section('content')
    <h3 class="page-title">Exams</h3>    
    @if(Session::has("flash_message_error")) 
            <div class="alert alert-error alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div> 
          @endif 

    @if(Session::has("flash_message_success")) 
        <div class="alert alert-info alert-block">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>{!! session('flash_message_success') !!}</strong>
        </div> 
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
            	<form method="post" action="/admin/events/create">{{ csrf_field() }}
            		<div class="col-xs-12 form-group">
	                	<div class="form-group">
	                        <label>Select Course</label>
	                        <select class="form-control" name="course_id" required>
	                        	<option>Select Course</option>
	                        	@foreach($my_courses as $course)
	                        		<option value="{{ $course->course_id}}">{{ $course->course->title}}</option>
	                        	@endforeach
	                        </select>
	                    </div> 
	                </div>
	                <div class="col-xs-12 form-group">
                    	<label for="exampleInputEmail1">Exam Title</label>
                    	<input type="text" name="exam_title" class="form-control" id="exampleInputEmail1" placeholder="Enter Title" required>
	                </div>

                    <!-- <div class="col-xs-12 form-group">
                        <table class="table table-bordered" id="dynamic_field">  
                            <tr>
                                <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td> 
                                <tr id="dynamic_field2" style="width: 50%">  
                                    <td>
                                        <input type="text" name="name2[]" placeholder="Option 1" class="form-control" />
                                    </td> 
                                </tr> 
                            </tr>
                            <td><button type="button" name="add" id="add2" class="btn btn-success">Add Answer</button></td> 

                        </table>
                        <button type="button" name="add" id="add" class="btn btn-success">Add More</button> 
                    </div> -->

                    <table class="table table-bordered" id="dynamic_field">
                        <!-- <div class="col-xs-12 form-group"> -->
                            <tr>
                                <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td> 
                                <div class="col-xs-12 form-group" id="dynamic_field2">
                                    <tr>  
                                        <td>
                                            <input type="text" name="name2[]" placeholder="Option 1" class="form-control" />
                                        </td> 
                                    </tr>
                                </div>
                                 
                            </tr>
                            <td><button type="button" name="add" id="add2" class="btn btn-success">Add Answer</button></td>
                        <!-- </div> -->
                    </table>
                    <button type="button" name="add" id="add" class="btn btn-success">Add Question</button> 

                             
                           <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  
	                

	                <!-- <div class="col-xs-12 form-group">
	                	<button type="submit" class="btn btn-primary"> Create Exam</button>
	                </div> -->
	                

                </form>
            </div>
            <p>
                <a href="{{ url('/admin/exams') }}" class="btn btn-default">Back to list</a>
            </p>
        </div> 
    </div> 

@endsection
@section('javascript')
 <script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
      $('#add2').click(function(){  
           i++;  
           $('#dynamic_field2').append('<tr id="row'+i+'"><td><input type="text" name="name2[]" placeholder="Option 2" class="form-control" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      }); 
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){            
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
 </script>
@stop      