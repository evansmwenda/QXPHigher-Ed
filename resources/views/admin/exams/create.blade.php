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
  //toggles create choice / text question buttons
  $("[data-toggle='toggle']").click(function() {
      var selector = $(this).data("target");
      $(selector).toggleClass('in');
  });


  $(document).ready(function () {
      // Unique identifier.
      let count = 0;

      // Global variables to help maintain state.
      let parentElement;
      let selectElement;

      // Add a question choice, plus a few buttons.
      $('.add-question').click(function () {
          count++;
          $('#question-wrapper').append(`\
              <div id="question-${count}" class="q-wrap">
                  <div class="q-wrapper">
                    <div class="form-group">
                        <label for="Q${count}" style="float:left;padding-top:3px;">${count} .</label>
                        <span style="display: block;overflow: hidden;padding: 0px 4px 0px 6px;">
                          <input type="text" id="Q${count}" style="font-weight:bold;display:inline-block;width:80%" class="form-control" placeholder="Question ${count}" name="question-${count}"><button type="button" name="remove" id="${count}" class="btn btn-danger btn_remove" style="display:inline-block">X</button>
                        </span>
                    </div>
                  </div>
                  <div class="option-wrapper"></div>
                  <div class="form-group" style="padding-left:20px;">
                      <button type="button" class="btn btn-secondary">Add Answer</button>
                      <button type="button" id="${count}" class="btn btn-secondary open-modal add-option-${count}">Add Options</button>
                      <select name="option" id="optionSelect-${count}" class="optionSelect hidden">
                          <option disabled>Number of options</option>
                          <option value="0">0</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                      </select>
                  </div>
              </div>` );
      });

      // Add a question text
      $('.add-question-text').click(function () {
          count++;
          $('#question-wrapper').append(`\
              <div id="question-${count}" class="q-wrap">
                  <div class="q-wrapper">
                    <div class="form-group" >
                        <label for="Q${count}" style="float:left;padding-top:3px;">${count} .</label>
                        <span style="display: block;overflow: hidden;padding: 0px 4px 0px 6px;">
                          <input type="text" id="Q${count}" style="font-weight:bold;display:inline-block;width:80%" class="form-control" placeholder="Question ${count}" name="question-${count}"><button type="button" name="remove" id="${count}" class="btn btn-danger btn_remove" style="display:inline-block">X</button>
                        </span>
                    </div>    
                  </div>
                  <div class="option-wrapper"></div>
                  <div class="form-group">
                      <span style="padding-left:20px;">
                        <textarea id="" name="w3review" rows="4" cols="50">Enter Answer</textarea>
                      </span> 
                      
                  </div>
              </div>` );
      });

      $('#question-wrapper').on("click", `.open-modal`, function (event) {
          // Safety measure to prevent any mishaps/overrides.
          event.preventDefault();

          // Assign values to the global variables.
          // parentElement = $('.open-modal').closest(`#question-${count}`).attr("id");
          parentElement = `question-${this.id}`;//use this to set focus to each add options button
          selectElement = $(`#${parentElement} select`).attr("id");


          // Toggle the select element's visibility.
          $(`#${selectElement}`).toggleClass("hidden");

          /**
           * Attach an event listener to the specified select element's 'change' event.
           */
          $(`#${parentElement}`).on("change", `select`, function (argument) {
              // Number of options to add.
              let numOfOptions = $(this).val();

              // Simple validation (zero-check & NaN check).
              if (numOfOptions !== 0 && !Number.isNaN(numOfOptions)) {
                  // Clear the contents of the parent element before adding new child elements.
                  $(`#${parentElement} .option-wrapper`).empty();
                  let j;
                  for (j = 0; j < numOfOptions; ++j) {
                      /**
                       * @method first() - grabs the first matched element specified by the selector.
                       * @method after() - inserts the HTML element after the end of the element match by 'first()'.
                       * @param Integer j - unique identifier (use it to assign a unique id to the elements)
                       * @todo [description]
                       */
                      $(`#${parentElement} .option-wrapper`).append(`<div class="form-group" style="padding-left:20px;width:60%"><input type="text" class="form-control" name="${j+1}" placeholder="Option ${j+1}"></div>`);
                  }
              }
              // Toggle the select element's visibility.
            $(`#${selectElement}`).addClass("hidden");
          })  
      });

      

      //delete questions
      $(document).on('click', '.btn_remove', function(){  
             var button_id = $(this).attr("id");   
             $('#question-'+button_id+'').remove();  
        });

      $('#question-form').submit(function (argument) {
        argument.preventDefault();

        // Parent element containing each question and it's related options.
        let temp_d = $(`#question-wrapper .q-wrap`);

        // Cycle through all 'containers', gather each question and it's option/s.
        $(temp_d).each(function () {
          // Variable definitions.
          let form_data = [];
          // To hold question related data.
          let q_name;
          let q_value;
          // To hold input data.
          let temp_o_data = [];
          let temp_q = $(this).find('div.q-wrapper .form-group input');
          let temp_o = $(this).find('div.option-wrapper .form-group input');
          q_name = $(temp_q).attr("name");
          q_value = $(temp_q).val();

          // Loop through the options/s (incase there's more than one).
          $(temp_o).each(function () {
            let _name= $(this).attr('name');
            let _value = $(this).val();
            temp_o_data.push({"name": _name, "value": _value});
          })

          // Format the data as a JSON object for submitting.
          form_data.push({
            [q_name]: {
              "name": q_name,
              "value": q_value
            },
            "options": temp_o_data
          });
          // console.log(form_data);

          //Submit the data.
          $.ajax({
            url: 'delete.php',
            type: 'POST',
            dataType: 'json',
            data: form_data,

          })
          .done(function(response) {
            console.log("we have liftoff->"+response.status);
            $("#mypar").html(response.success);
            $('#question-form')[0].reset();
          })
          .fail(function(response) {
            console.log("error->"+response);
          })
          // $.ajax({
          //   type: "POST",
          //   url: 'delete.php',
          //   dataType: "json",
          //   success: function(response){
          //       //if request if made successfully then the response represent the data
          //         console.log("we have liftoff->"+response.status);
          //         $("#mypar").html(response.success);
          //         $('#question-form')[0].reset();
          //       // $( "#result" ).empty().append( response );
          //   }
          // });
          
        })
      })

  })
</script>
@stop      