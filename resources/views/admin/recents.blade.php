
    <div class="lecturer">
        <h2>Highlights</h2>
    </div>
    <div class="admin-news">
        <div class="admin-news-content">
            <h2>{{ $my_summary_count['courses'] or 0}}</h2>
            <p>Courses</p>
            
        </div>
        <div class="admin-news-content">
            <h2>{{ $my_summary_count['events'] or 0}}</h2>
            <p>Events</p>
        </div>
        <div class="admin-news-content">
            <h2>{{ $my_summary_count['exams'] or 0}}</h2>
            <p>Exams</p>
        </div>
        <div class="admin-news-content">
            <h2>{{ $my_summary_count['assignments'] or 0}}</h2>
            <p>Assignments</p>
        </div>
    </div>

    <div class="lecturer" style="background: #11BECC">
        <h2>Requests for student enrolment</h2>
    </div>
      <div class="admin-news" style="overflow-y:scroll">
        <table class="table tab-default table-bordered table-striped">
          <thead>
            <tr>
              <td>#</td>
              <td>Student Name</td>
              <td>Requested Course</td>
            </tr>

          </thead>
          <tbody>
             <a href=""><tr>
                <td>1</td>
                <td>John Doe</td>
                <td>Biology</td>
              </tr>
             </a>

          </tbody>
        </table>
    </div>



