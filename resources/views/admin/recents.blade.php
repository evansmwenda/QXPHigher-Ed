
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
              <td>View</td>
            </tr>
          </thead>
          <tbody>
            @foreach ($request as $key => $item)
                
                @if($item->status =='Pending')
                <tr style="font-weight: 900">
                    <td>{{++$key}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->title}}</td>
                    <td>
                        <form action="{{ url('admin/request_details',$item->id) }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" value="{{$item->id}}" name="request_id">          
                            <button style="background: #079DFF;color:#fff; border-radius:10px; border:1px solid transparent; font-size:10px" type="submit">View</button>
                        </form>
                    </td>
                </tr>

                @endif
            @endforeach
            <tr>
            <td colspan="5" style="text-align: center;color:#060646;"><a href="{{url('admin/studentrequests')}}">All Requests</a> </td>
            </tr>
          </tbody>
        </table>
    </div>



