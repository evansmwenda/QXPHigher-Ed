
    <div class="lecturer">
        <h2>Highlights</h2>
    </div>
    <div class="admin-news">
        <div class="admin-news-content">
            <h2>{{ $highlights['courses'] or 0}}</h2>
            <p>Courses</p>
            
        </div>
        <div class="admin-news-content">
            <h2>{{ $highlights['events'] or 0}}</h2>
            <p>Events</p>
        </div>
        <div class="admin-news-content">
            <h2>{{ $highlights['exams'] or 0}}</h2>
            <p>Exams</p>
        </div>
        <div class="admin-news-content">
            <h2>{{ $highlights['assignments'] or 0}}</h2>
            <p>Assignments</p>
        </div>
    </div>

    <div class="lecturer" style="background: #11BECC">
        <h2>Requests for student enrolment</h2>
    </div>
      <div class="admin-news" style="overflow-y:scroll">
        <table class="table tab-default table-bordered table-striped">
          <thead>
            <tr style="font-weight: 900">
              <td>#</td>
              <td>Student Name</td>
              <td>Requested Course</td>
              <td>View</td>
            </tr>
          </thead>
          <tbody>
              @if(count($request_enrollments) > 0)
                @foreach ($request_enrollments as $key => $item)
                    @if($item->status =='Pending')
                    <tr style="font-weight: 500">
                        <td>{{++$key}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->title}}</td>
                        <td>
                            <form action="{{ url('admin/students/requests/details',$item->id) }}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" value="{{$item->id}}" name="request_id">          
                                <button style="background: #079DFF;color:#fff; border-radius:10px; border:1px solid transparent; font-size:10px" type="submit">View</button>
                            </form>
                        </td>
                    </tr>

                    @endif
            @endforeach
            <tr>
                <td colspan="5" style="text-align: center;color:#060646;"><a href="{{url('admin/students/requests')}}">All Requests</a> </td>
            </tr>
              @else 
                <tr>
                    <td colspan="5" style="text-align: center;color:#060646;">
                        No students have requested for enrollment
                    </td>
                </tr>
              @endif
            
          </tbody>
        </table>
    </div>



