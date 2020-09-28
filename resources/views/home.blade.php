@extends('layouts.app')

@section('content')
{{-- At glance boxes --}}
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box qxp-info text-center">
      <div class="inner">
        <h3>{{ $count_courses }}</h3>
      </div>
      <div class="icon">
        {{-- <i class="fa fa-book"></i> --}}
      </div>
      <a href="#" class="small-box-footer">Registered Courses</a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success text-center">
      <div class="inner">
        <h3>{{ $count_events }}</h3>
      </div>
      <div class="icon">
        {{-- <i class="fa fa-calendar"></i> --}}
      </div>
      <a href="#" class="small-box-footer">Created Events</a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box qxp-warning text-center">
      <div class="inner">
        <h3>{{ $count_exams }}</h3>

      </div>
      <div class="icon">
        {{-- <i class="ion ion-person-add"></i> --}}
      </div>
      <a href="#" class="small-box-footer">Exams</a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box qxp-danger text-center">
      <div class="inner">
        <h3>{{ $count_assignments }}</h3>

        <p></p>
      </div>
      <div class="icon">
        {{-- <i class="ion ion-pie-graph"></i> --}}
      </div>
      <a href="#" class="small-box-footer">Assignments </a>
    </div>
  </div>
  <!-- ./col -->
</div>
{{-- Courses and Scheduled events --}}
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Registered Courses</div>
            <div class="panel-body">
               <table class="table table-striped">
                @if($count_courses>0)
                   <tr>
                     <thead class="bg-info">
                      <td style="width: 10px">#</td>
                       <td>Course Name</td>
                       <td>Student Enrolled</td>
                       <td>Pricing</td>
                     </thead>
                   </tr>
                   <tbody>
                   
                    @foreach($courses->take(4) as $key=>$course)
                      <tr>
                        <td>{{ ++$key }}</td>
                         <td>{{ $course->course->title }}</td>
                         <td>25</td>
                         <td>{{ $course->course->price == null ? "Free":$course->course->price}}</td>
                       </tr>
                    @endforeach
                 @else
                    <p style="text-align: center">You have no courses</p>
                 @endif
                 </tbody>
               </table>
               <a href="{{url('/admin/courses')}}" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">Scheduled Live Events</div>
        <div class="panel-body">
           <table class="table table-striped">
            @if($count_events>0)
               <tr>
                 <thead class="bg-info">
                  <td style="width: 10px">#</td>
                   <td>Meeting Group</td>
                   <td>Date</td>
                   <td>Decription</td>
                 </thead>
               </tr>
               <tbody>
              
                @foreach($events->take(2) as $key=>$event)
                  <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                      <p style="margin-bottom: 0px !important">{{ $event->title}}</p>
                      <span style="font-size: .8em;color: grey;padding-top: 0px !important;">{{ $event->course_title }}</span>
                    </td>
                    <td>{{ $event->event_start_time }}</td>
                    <td>
                      Lorem Ipsum isLorem since the 1500s, when an unknown prias
                    </td>                
                   </tr>
                @endforeach
            @else
              <p style="text-align: center">You have no scheduled events</p>
            @endif


             </tbody>
           </table>
           <a href="{{ url('/admin/events') }}" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
  </div>
</div>
{{-- Exams and Asignments --}}
<div class="row">
  <div class="col-md-6">
      <div class="panel panel-default">
          <div class="panel-heading">Exams</div>
          <div class="panel-body">
             <table class="table table-striped">
              @if($count_exams>0)
                 <tr>
                   <thead class="bg-info">
                    <td style="width: 10px">#</td>
                     <td>Course Name</td>
                     <td>Exam Title</td>
                     <td>State | Published</td>
                   </thead>
                 </tr>
                 <tbody>
                
                  @foreach($exams->take(4) as $key=>$exam)
                    <tr>
                      <td>{{ ++$key }}</td>
                       <td>{{ $exam->course->title }}</td>
                       <td>{{ $exam->title }}</td>
                       <td>{{ $exam->published == "1" ? "Published":"Not Published"}}</td>
                     </tr>
                  @endforeach
                @else
                  <p style="text-align: center">You have no exams</p>
                @endif
                 

               </tbody>
             </table>
             <a href="{{ url('/admin/exams')}}" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">Assignments</div>
      <div class="panel-body">
         <table class="table table-striped">
          @if($count_assignments>0)
               <tr>
                 <thead class="bg-info">
                  <td style="width: 10px">#</td>
                   <td>Assignment Name</td>
                   <td>Course</td>
                   <td>Submitted Learners</td>
                  
                 </thead>
               </tr>
               <tbody>
              
                @foreach($assignments->take(4) as $key=>$assignment)
                  <tr>
                    <td>{{++$key}}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ $assignment->course->title }}</td>
                    <td>{{ count($submitted_assignments_array[$assignment->id]) }}</td>      
                   </tr>
                @endforeach
          @else
            <p style="text-align: center">You have no assignments</p>
          @endif
             

           </tbody>
         </table>
         <a href="{{ url('/admin/assignments') }}" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
      </div>
  </div>
</div>
</div>

{{-- Need Grading --}}
<div class="row">
  <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">Need Grading</div>
          <div class="panel-body">
             <table class="table table-striped">
               <tr>
                 <thead class="bg-info">
                  <td style="width: 10px">#</td>
                   <td>Student</td>
                   <td>Course</td>
                   <td>Assign Name</td>
                   <td>Grade/Marks/Score</td>
                 </thead>
               </tr>
               <tbody>
                 <tr>
                  <td>1</td>
                   <td>Elizaben Keen</td>
                   <td>Microsoft Dynamics</td>
                   <td>Payroll Integration addons</td>
                   <td>25/30</td>
                 </tr>

               </tbody>
             </table>
             <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
  </div>
</div>

{{-- Resources --}}
<div class="row">
  <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">Resources participation</div>
          <div class="panel-body">
             <table class="table table-striped">
               <tr>
                 @if(count($resources)>0)
                   <thead class="bg-info">
                    <td style="width: 10px">#</td>
                     <td>Resource</td>
                     <td>Course</td>
                     <td>No. Views</td>
                     
                   </thead>
                    </tr>
                    <tbody>
                   @foreach($resources->take(4) as $key=>$resource)
                    <tr>
                      <td>{{ ++$key }}</td>
                       <td>{{ $resource->name }}</td>
                       <td>{{ $resource->course->title }}</td>
                       <td>25/30</td>
                    </tr>
                   @endforeach

                 @else
                    <p style="text-align: center">You have no downloadable resources</p>
                 @endif
                 
               
                

               </tbody>
             </table>
             <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
  </div>
</div>

@endsection
