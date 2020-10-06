@extends('layouts.app')

@section('content')
{{-- At glance boxes --}}
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box qxp-info text-center">
      <div class="inner">
        <h3>150</h3>
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
        <h3>53</h3>
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
        <h3>44</h3>

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
        <h3>65</h3>

        <p></p>
      </div>
      <div class="icon">
        {{-- <i class="ion ion-pie-graph"></i> --}}
      </div>
      <a href="#" class="small-box-footer">Quizes </a>
    </div>
  </div>
  <!-- ./col -->
</div>
{{-- Coarses and Schedukled events --}}
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Registered Coarses</div>
            <div class="panel-body">
               <table class="table table-striped">
                 <tr>
                   <thead class="bg-info">
                    <td style="width: 10px">#</td>
                     <td>Coarse Name</td>
                     <td>Student Enrolled</td>
                     <td>Pricing</td>
                   </thead>
                 </tr>
                 <tbody>
                   <tr>
                    <td>1</td>
                     <td>Biology 101</td>
                     <td>25</td>
                     <td>$12</td>
                   </tr>
                   <tr>
                    <td>2</td>
                    <td>Microsoft Package</td>
                    <td>5</td>
                    <td>$12</td>
                  </tr>
                 </tbody>
               </table>
               <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">Scheduled Live Events</div>
        <div class="panel-body">
           <table class="table table-striped">
             <tr>
               <thead class="bg-info">
                <td style="width: 10px">#</td>
                 <td>Meeting Group</td>
                 <td>Date</td>
                 <td>Decription</td>
                 <td>Action</td>
               </thead>
             </tr>
             <tbody>
               <tr>
                <td>1</td>
                <td>
                  <p style="margin-bottom: 0px !important">Title</p>
                  <span style="font-size: .8em;color: grey;padding-top: 0px !important;">Subject</span>
                </td>
                <td>22/01/2020 12:00</td>
                <td>
                  Lorem Ipsum isLorem since the 1500s, when an unknown prias
                </td>
                <td><a href="">View</a></td>                     
               </tr>

             </tbody>
           </table>
           <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
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
               <tr>
                 <thead class="bg-info">
                  <td style="width: 10px">#</td>
                   <td>Coarse Name</td>
                   <td>Exam Title</td>
                   <td>State | Published</td>
                 </thead>
               </tr>
               <tbody>
                 <tr>
                  <td>1</td>
                   <td>Biology 101</td>
                   <td>Biology</td>
                   <td>Not Published</td>
                 </tr>

               </tbody>
             </table>
             <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">Assignments</div>
      <div class="panel-body">
         <table class="table table-striped">
           <tr>
             <thead class="bg-info">
              <td style="width: 10px">#</td>
               <td>Assignment Name</td>
               <td>Course</td>
               <td>Submitted Learners</td>
              
             </thead>
           </tr>
           <tbody>
             <tr>
              <td>1</td>
              <td>Microsoft Dynamics Business central 365</td>
              <td>Microsoft Package</td>
              <td>1</td>      
             </tr>

           </tbody>
         </table>
         <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
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
                 <thead class="bg-info">
                  <td style="width: 10px">#</td>
                   <td>Resource</td>
                   <td>Course</td>
                   <td>No. Views</td>
                   
                 </thead>
               </tr>
               <tbody>
                 <tr>
                  <td>1</td>
                   <td>Resource Name</td>
                   <td>Microsoft Dynamics</td>
                   <td>25/30</td>
                 </tr>

               </tbody>
             </table>
             <a href="#" class="small-box-footer pull-right">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
      </div>
  </div>
</div>

@endsection
