<div class="lecturer">
    <span class="fa fa-user fa-2x"></span>
    <h2>{{\Auth::user()->name}}</h2>
     <i>Lecturer</i>
</div>
<div class="row">
    <div class="col-md-6 admin-display">
        <h2>{{ $my_summary_count['courses'] or 0}}</h2>
      <p>Courses</p>
       
    </div>
    <div class="col-md-6 admin-display">
        <h2>{{ $my_summary_count['events'] or 0}}</h2>
        <p>Events</p>
    </div>
    <div class="col-md-6 admin-display">
        <h2>{{ $my_summary_count['exams'] or 0}}</h2>
        <p>Exams</p>
    </div>
    <div class="col-md-6 admin-display">
        <h2>{{ $my_summary_count['assignments'] or 0}}</h2>
        <p>Assignments</p>
    </div>
</div>
<hr>