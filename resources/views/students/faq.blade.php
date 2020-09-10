@extends('layouts.home')

@section('main')
    <div class="row">
        <div class="col-md-8">
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
            <div class="panel-group" id="faqAccordion">
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: What is QXP Meet?</p>
              </h4>

            </div>
            <div id="question0" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>With everyone moving online and relying heavily on conferencing to carry out meetings, QXP is an easy, simple and flexible conferencing solution that cuts down the hassle associated with conferencing and makes it easier on you.
                        </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question1">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: How do I join a meeting/class?</p>
              </h4>

            </div>
            <div id="question1" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>Sign up <a href="http://www.qxpacademy.com">here for QXP</a> and choose your plan. Once you log in, the page will direct you to your dashboard and you can choose the class you have been invited to attend. The page will then direct you to the live classroom/meeting.  You can join either by audio or listen only modes. Select the desired one and join the meeting. If you select audio, you will perform a private echo test to ensure that your microphone is working properly</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question2">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: What is a meeting ID or link?</p>
              </h4>

            </div>
            <div id="question2" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p> This is a link that a meeting organizer or an SME (Subject Matter Expert) generates and shares with the meeting/class attendees. You can clink on that link and directly join a class in progress. </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question3">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: Do I need an account to join?</p>
              </h4>

            </div>
            <div id="question3" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>You do not require a QXP account to join a meeting/class you have been invited to. However, you need an account to create a meeting/class and access a recording of the meeting. </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question4">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: Do I need to have a webcam to join a meeting?</p>
              </h4>

            </div>
            <div id="question4" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>You can join a meeting with audio only, share your screen, share the whiteboard and chat with the other participants. You only need a webcam if there are required to transmit your video to the participants </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question5">
                 <h4 class="panel-title">
                    <p style="cursor:pointer" class="ing">Q: Am I able to join from an area with poor internet connectivity?</p>
              </h4>

            </div>
            <div id="question5" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>QXP is not as bandwidth intensive as most conference providers. You can join in and listen with the most basic internet connection. </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question6">
                 <h4 class="panel-title">
                    <p class="ing" style="cursor: pointer">Q: If I have no access to internet, can I join by dialing in?</p>
              </h4>

            </div>
            <div id="question6" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>You can join a meeting/class by dialing in the numbers provided for each region. The numbers will be on your meeting invite just below the link.</p>
                </div>
            </div>
        </div>

    </div>
        </div>
    </div>
@endsection
