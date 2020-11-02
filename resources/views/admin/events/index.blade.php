@extends('layouts.app')

@section('content')
    <div class="row">
        @include('students.header')
    </div>
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
    
    <div class="row">
        <div class="col-md-4" style="background: #fff">
            <div class="sticky-top mb-3">
              <div class="card">
                <div class="card-header">
                  <h4>Schedule 
                    <a href="{{ url('/admin/events/create') }}" class="my-btn my-btn-warning">
                        <span class="fa fa-plus" style="margin-left: 0px !important"></span>Add
                    </a>
                  </h4>
                  
                </div>
                <div id="external-events">
                </div>
              </div>
            </div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Class</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">All</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Exams</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade in active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
        
                  @if(count($assignment_event_array) > 0)
                      @foreach($assignment_event_array as $assignment_event)
                        <div class="events-activity">
                          <h4>{{$assignment_event['title']}} {{$assignment_event['start']}}</h4>
                          <span>{{$assignment_event['days']}} Day(s) ago</span>
                          <div style="text-align:center;padding:4px;float:right;height:20px;width:60px;background-color:#FD6C03;">
                            <a href="{{ url('/admin/events/update/'.$assignment_event['id']) }}" style="background-color:#FD6C03 ">Edit</a>
                          </div>
                        </div>
                      @endforeach
                      
                    @else
                      <p class="text-center">You have no upcoming assignments</p>
                    @endif
                </div>
        
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  @if(count($class_event_array) > 0)
                    @foreach($class_event_array as $class_event)
                      <div class="events-activity">
                        <h4>{{$class_event['title']}} {{$class_event['start']}}</h4>
                        <span>{{$class_event['days']}} Day(s) ago</span>
                        <div style="text-align:center;padding:4px;float:right;height:20px;width:60px;background-color:#FD6C03;">
                          <a href="{{ url('/admin/events/update/'.$class_event['id']) }}" style="background-color:#FD6C03 ">Edit</a>
                        </div>
                      </div>
                    @endforeach
                    
                  @else
                    <p class="text-center">You have no upcoming classes</p>
                  @endif
                </div>
              </div>
              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                  @if(count($exam_event_array) > 0)
                      @foreach($exam_event_array as $exam_event)
                        <div class="events-activity">
                          <h4>{{$exam_event['title']}} {{$exam_event['start']}}</h4>
                          <span>{{$exam_event['days']}} Day(s) ago</span>
                          <div style="text-align:center;padding:4px;float:right;height:20px;width:60px;background-color:#FD6C03;">
                            <a href="{{ url('/admin/events/update/'.$exam_event['id']) }}" style="background-color:#FD6C03 ">Edit</a>
                          </div>
                        </div>
                      @endforeach
                      
                  @else
                    <p class="text-center">You have no upcoming exams</p>
                  @endif
                </div>
              </div>
            </div>
        </div>
        <div class="col-md-8 calendar">
            <div id="calendar"></div>
        </div>
    </div>
    

    <div class="panel panel-default" style="margin-top: 500px">
        <div class="panel-heading">
            @lang('global.app_create')
        </div>
        
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($my_events) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Event Title</th>
                        <th>Course</th>
                        <th>Event Time</th>
                        <th>Action(s)</th>
                     
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($my_events) > 0)
                    	@foreach($my_events as $event)
                    		<tr data-entry-id="{{ $event->id }}">
	                            <td>{{ $event->id }}</td>
	                            <td>{{ $event->title }}</td>
	                            <td>{{ $event->course->title}}</td>
	                            <td>{{ $event->event_start_time}}</td>
	                            <td><a href="{{ url('/admin/events/delete/'.$event->id)}}" class="btn btn-danger btn-sm">Delete</a></td>
	                        </tr>
                    	@endforeach
                        
                    @else
                        <tr>
                            <td colspan="10">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('javascript')
    @parent

<script>
	var event_array = <?php echo json_encode($event_array, JSON_PRETTY_PRINT) ?>;
	console.log( event_array[1]); // David Flanagan
	console.log(String(event_array).replace(/['"]+/g, ''));
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction','list', 'dayGrid', 'timeGrid' ],
      header    : {
        //left  : 'prev,next today',
        left: 'title',
        // right : 'dayGridMonth,timeGridWeek,timeGridDay',
        //right : 'dayGridMonth,timeGridWeek,timeGridDay',
    
      
      },
      buttonIcons: {
        next: 'right-single-arrow', 
        prevYear: 'left-double-arrow', 
        nextYear: 'right-double-arrow'
      },

      'themeSystem': 'bootstrap',
      events: event_array,
      eventTextColor: '#fff',
       // events : <?php //echo json_encode($books, JSON_PRETTY_PRINT);?>,
      // events    : [
      //   {
      //     title          : 'All Day Event',
      //     start          : new Date(y, m, 1),
      //     backgroundColor: '#f56954', //red
      //     borderColor    : '#f56954', //red
      //     allDay         : true
      //   },
      //   {
      //     title          : 'Long Event',
      //     start          : new Date(y, m, d - 5),
      //     end            : new Date(y, m, d - 2),
      //     backgroundColor: '#f39c12', //yellow
      //     borderColor    : '#f39c12' //yellow
      //   },
      //   {
      //     title          : 'Meeting',
      //     start          : new Date(y, m, d, 10, 30),
      //     allDay         : false,
      //     backgroundColor: '#0073b7', //Blue
      //     borderColor    : '#0073b7' //Blue
      //   },
      //   {
      //     title          : 'Lunch',
      //     start          : new Date(y, m, d, 12, 0),
      //     end            : new Date(y, m, d, 14, 0),
      //     allDay         : false,
      //     backgroundColor: '#00c0ef', //Info (aqua)
      //     borderColor    : '#00c0ef' //Info (aqua)
      //   },
      //   {
      //     title          : 'Birthday Party',
      //     start          : new Date(y, m, d + 1, 19, 0),
      //     end            : new Date(y, m, d + 1, 22, 30),
      //     allDay         : false,
      //     backgroundColor: '#00a65a', //Success (green)
      //     borderColor    : '#00a65a' //Success (green)
      //   },
      //   {
      //     title          : 'Click for Google',
      //     start          : new Date(y, m, 28),
      //     end            : new Date(y, m, 29),
      //     url            : 'http://google.com/',
      //     backgroundColor: '#3c8dbc', //Primary (light-blue)
      //     borderColor    : '#3c8dbc' //Primary (light-blue)
      //   }
      // ],
      editable  : false,//true
      droppable : false, //true // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }    
    });

    //calendar.changeView('timeGridDay');
    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#060646' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff',
        'eventTextColor'  : '#fff'
       }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      ini_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>

@stop

