<div class="notification-messages">
    <div class="message-header">
        <h3>Recent Activities</h3>
    </div>
    <table class="table table-bordered">
        <tbody>
            @foreach ($messages as $message)
                @if($message->read ==0)
                <tr>
                    <td style="font-weight: 900">
                    <form action="{{url('/update_message',$message->id)}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit">
                                <i class="fa fa-envelope"></i>
                                @if($message->status =='Pending')
                                 You send a request to Joshua for enrollment to biology on <i>{{$message->created_at }}</i>
                                 @elseif($message->status =='Accepted')
                                Your request for enrollment to biology has been accepted
                                @else
                                Your request for enrollment to biology has been rejected
                                 @endif
                            </button>
                        </form>
                    </td>
                <tr>
                @else
                <tr>
                    <td>
                        <form action="{{url('/update_message',$message->id)}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit">
                                <i class="fa fa-envelope"></i>
                                @if($message->status =='Pending')
                                 You send a request to Joshua for enrollment to biology on <i>{{$message->created_at }}</i>
                                 @elseif($message->status =='Accepted')
                                Your request for enrollment to biology has been accepted
                                @else
                                Your request for enrollment to biology has been rejected
                                 @endif
                            </button>
                        </form>
                    </td>
                <tr>
                @endif
            @endforeach
            </tr>
        </tbody>
    </table>

</div>
