<div class="col-md-8" style="background-color: red">
    <div class="row top-header-2">

        <div class="col-md-12 col-sm-12" >
            <div class="col-sm-6">
                <div class="form-group has-search">
                    <input type="text" class="form-control" placeholder="Search">
                  </div>
            </div>
            <div class="col-md-6">
                {{-- <div class="col-sm-2">
                    <span class="fa fa-shield-alt fa-2x"></span>
                </div>
                <div class="col-sm-2">
                     <span class="fa fa-bell fa-2x"></span>
                </div> --}}
                <div class="col-sm-2">
                     <a href="/calender"><span class="fa fa-calendar-alt fa-2x"></span></a>
                </div>
            </div>

         </div> 
    
    </div>
</div>
<div class="col-md-4 dashboard-right" style="background-color: green">
    <div class="row top-right">
    <a href=""><i class="fa fa-user"></i>  {{\Auth::user()->name}}</a>
            <a href="#" class="sidebar-toggle pull-right" data-toggle="offcanvas" role="button">
               <span class="sr-only">Toggle navigation</span>
               <span class="fa fa-bars"></span>
           </a> 

   </div>
</div>