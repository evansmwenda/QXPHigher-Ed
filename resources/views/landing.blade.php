@extends('layouts.qxphome')

@section('main')
 <div class="qxp-higered text-center">
    <img class="qxp-logo" src="{{asset('images/logo/bgAsset6.svg')}}" style="margin-top: 100px 0px" width="300" height="200">
 </div>

 <div class="container">
    <div class="qxp-higered-back">
        <img src="{{asset('images/featured/bgAsset-3-1536x907.png')}}" >
    </div>
 </div>

 <div class="container text-center qxp-higher-content">
        <h3>QXP for Pre Primary, Primary and Secondary Education</h3>
        <p>Whether a few or hundreds of students, post-secondary students can attend classes virtually from wherever they are, access learning calendars and schedules, assignments, take tests and interact with their lecturers.</p>
 </div>
 {{-- Icon view --}}
 <section id="tw-facts" class="qxp-facts">
    <div class="container"> 
       <!-- Row End -->
       <div class="row">
          <div class="col-md-4 col-sm-12 qxp-section" style="margin: 20px 0px; ">
                <div class="row">
                </div>
                <h3>Enrich teaching & learning</h3>
                <p>Expand traditional classrooms with video communications to meet the growing needs of modern learners.</p>       
          </div>
 
          <div class="col-md-4 col-sm-12 qxp-section" style="margin: 20px 0px; ">
             <div class="facts-img">
                <div class="row">
                </div>
                <h3>Maximize your resources</h3>
                <p>Students can join classes virtually, from any device , boosting attendance and retention.</p>       
         
             </div>
          </div>
 
          <div class="col-md-4 col-sm-12 qxp-section" style="margin: 20px 0px; ">
             <div class="facts-img">
                <div class="row">
                  </div>
                  <h3>Dynamic Content Sharing</h3>
                  <p>Increase student participation and learning retention with virtual and hybrid classrooms and micro learning.</p>       
             </div>
          </div>
       </div>
       <hr>
       <div class="row">
         <div class="col-md-3 col-sm-12 qxp-section" style="margin: 20px 0px; ">
            <div class="facts-img">
               <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding:auto;margin: auto">
                     <img src="{{asset('images/icons/011-video-conference-3.svg')}}" width="50" height="60">
                  </div>
                 </div>
                  <p>HD video and audio provide exceptional clarity and quality to virtual and hybrid classes.</p>
            </div>
         </div>

         <div class="col-md-3 col-sm-12 qxp-section" style="margin: 20px 0px; ">
            <div class="facts-img">
               <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding:auto;margin: auto">
                     <img src="{{asset('images/icons/017-coaching.svg')}}" width="50" height="60">
                  </div>
                  </div>
                  <p>Students easily access learning anytime, anywhere, on Any device or strength of connection.</p>

            </div>
         </div>


         <div class="col-md-3 col-sm-12 qxp-section" style="margin: 20px 0px; ">
            <div class="facts-img">
               <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding:auto;margin: auto">
                     <img src="{{asset('images/icons/003-video-call-1.svg')}}" width="50" height="60">
                  </div>
                  <p>Simple user management and single sign on make video a seamless component of the learning experience.</p>
                
                    </div>

            </div>
         </div>

         <div class="col-md-3 col-sm-12 qxp-section" style="margin: 20px 0px; ">
            <div class="facts-img">
               <div class="row">
                  <div class="col-md-12 col-sm-12" style="padding:auto;margin: auto">
                     <img src="{{asset('images/icons/011-report.svg')}}" width="50" height="60">
                  </div>
                </div>
                  <p>Session recording and automatic transcription allow students to learn at their own pace.</p>

               </div>

         </div>

       </div>
    </div>
    <!-- Container End -->
 </section>
<!--  {{-- courasel --}} -->
<div id="my-carousel">
  <div class="tw-hero-slider owl-carousel">

  <div class="slider-1 slider-map-pattern">
     <!-- Slider arrow end -->
     <div class="slider-wrapper d-table">
        <div class="slider-inner d-table-cell">
           <div class="container">
              <div class="row">
                  <div class="col-lg-12 col-sm-12">
                     <div class="slider-content">
                       <img src="{{asset('images/sliders/200515-QXP-Customer-Experience_XUI-2.jpg')}}">
                     </div>
                  </div>
                  <!-- Col end -->
                  <!-- col end -->
               </div>
           </div>
           <!-- Container End -->
        </div>
        <!-- Slider Inner End -->
     </div>
     <!-- Slider Wrapper End -->
  </div>
  <!-- Slider 1 end -->


 <div class="slider-2">
     <div class="slider-wrapper d-table">
        <div class="slider-inner d-table-cell">
           <div class="container">
              <div class="row">
                 <div class="col-lg-12 col-sm-12">
                    <div class="slider-content">
                      <img src="{{asset('images/sliders/200515-QXP-Customer-Experience_XUI-3.jpg')}}">
                    </div>
                 </div>
                 <!-- Col end -->
                 <!-- col end -->
              </div>
              <!-- Row End -->
           </div>
           <!-- Container End -->
        </div>
        <!-- Slider Inner End -->
     </div>
     <!-- Slider Wrapper End -->
  </div>
  <!-- Slider 2 end -->


  <div class="slider-3">
     <div class="slider-wrapper d-table">
        <div class="slider-inner d-table-cell">
           <div class="container">
              <div class="row">
                  <div class="col-lg-12 col-sm-12">
                     <div class="slider-content">
                       <img src="{{asset('images/sliders/200515-QXP-Customer-Experience_XUI-4.jpg')}}">
                     </div>
                  </div>
                  <!-- Col end -->
                  <!-- col end -->
               </div>
           </div>
           <!-- COntainer End -->
        </div>
        <!-- Slider Inner End -->
     </div>
     <!-- Slider Wrapper End -->
  </div> 

  <!-- Slider 3 end -->
  {{-- end slider 4 --}}
  <div class="slider-4">
      <div class="slider-wrapper d-table">
         <div class="slider-inner d-table-cell">
            <div class="container">
               <div class="row">
                   <div class="col-lg-12 col-sm-12">
                      <div class="slider-content">
                        <img src="{{asset('images/sliders/200515-QXP-Customer-Experience_XUI-6.jpg')}}">
                      </div>
                   </div>
                   <!-- Col end -->
                   <!-- col end -->
                </div>
            </div>
            <!-- COntainer End -->
         </div>
         <!-- Slider Inner End -->
      </div>
      <!-- Slider Wrapper End -->
   </div> 
</div>
</div>


 <div class="qxp-subfooter">
     <div class="container">
         <div class="row">
            <div class="col-md-10">
                <h3>Give Your Students Knowledge Without Borders</h3>
            </div>
            <div class="col-md-2">
                <a href="#"class="btn btn-warning">SIGN UP</a>
            </div>
         </div>
     </div>
 </div>
@endsection
