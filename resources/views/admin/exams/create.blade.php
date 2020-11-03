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
              <form id="question-form" method="post">{{ csrf_field() }}
                  <div class="col-xs-12 form-group">
                    <meta name="csrf-token" content='{{ csrf_token() }}'/>
                    <div class="form-group">
                          <label>Select Course</label>
                          <select class="form-control" name="course_id" id="course_id" required>
                            <option>Select Course</option>
                            @foreach($my_courses as $course)
                              <option value="{{ $course->course_id}}">{{ $course->course->title}}</option>
                            @endforeach
                          </select>
                      </div> 
                  </div>

                  <div class="col-xs-12 form-group">
                      <label for="examTitle">Exam Title</label>
                      <input type="text" name="exam_title" id="exam_title" class="form-control" placeholder="Enter Title" required>
                  </div>
                  <div class="col-xs-12 form-group">
                    <label for="exampleDescription">Description</label>
                    <textarea name="description" class="form-control" id="exam_description" rows="3"></textarea>
                  </div>

                  <div id="question-wrapper">
                  </div>

                  <div class="col-xs-12 form-group">
                      <button type="button" class="btn btn-info btn-sm" 
                      data-toggle="toggle" data-target="#demo">
                          <i class="glyphicon glyphicon-plus"></i>&nbsp;Question
                      </button>

                      <div id="demo" class="">
                          <button class="btn btn-default add-question" type="button" style="margin: 5px 0px;">
                              <span class="glyphicon glyphicon-record" style=""></span> Choice
                          </button>
                          <button class="btn btn-default add-question-text" type="button" style="margin: 5px 0px;">
                              <span class="glyphicon glyphicon-comment" style=""></span> Text
                          </button>
                      </div>

                  </div>

                  <div class="col-xs-12 form-group">
                      <!-- <button class="btn btn-primary add-question" type="button">Add Question</button> -->
                      <!-- <button type="submit">Submit</button> -->
                      <input type="submit" value="Submit" class="btn btn-primary" />
                  </div>
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
                      <button type="button" class="btn btn-secondary" style="display:none">Add Answer</button>
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
              console.log("clicked submit");

              // Parent element containing each question and it's related options.
              let temp_d = $(`#question-wrapper .q-wrap`);

              let form_data = [];
              // Cycle through all 'containers', gather each question and it's option/s.
              $(temp_d).each(function () {
                // Variable definitions.
                // let form_data = [];

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
                  /*[q_name]*/ "question": {
                    "name": q_name,
                    "value": q_value
                  },
                  "options": temp_o_data,
                  "total":count,
                  "course_id": $('#course_id').val(),
                  "exam_title":  $('#exam_title').val(),
                  "description":  $('#exam_description').val()
                });
                
              }); //end of foreaach


             console.log(JSON.stringify(form_data));


              //Submit the data.
              $.ajax({
                url: '/admin/exams/save',
                type: 'POST',
                beforeSend: function (request) {
                  
                  return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                dataType: 'json',
                data: JSON.stringify(form_data),

              })
              .done(function(response) {
                console.log("we have liftoff->"+response.status);
                console.log("data->"+response.sent);
                console.log("datte now->"+response.date_now);
                console.log("date valuid->"+response.date_valid);
                $("#mypar").html(response.success);
                $('#question-form')[0].reset();
                // window.location.replace("/admin/exams");
              })
              .fail(function(response) {
                console.log("error->"+response);
              });

              //end of ajax submit

              // window.location.reload();
            })
      // window.location.reload();

  })
</script>
@stop      