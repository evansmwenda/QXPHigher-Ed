<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<!-- <script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/select2.full.min.js"></script>
<script src="{{ url('adminlte/js') }}/main.js"></script>

<script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ url('adminlte/js/app.min.js') }}"></script>

<!-- added to create datetime range picker in events -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- end of added scripts for events -->



<!-- script added for the admin lte calender -->
<!-- jQuery -->
<!-- <script src="../plugins/jquery/jquery.min.js"></script> -->
<!-- Bootstrap -->
<!-- <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- jQuery UI -->
<!-- <script src="../plugins/jquery-ui/jquery-ui.min.js"></script> -->
<!-- AdminLTE App -->
<script src="{{ url('adminlte/myplugins/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('adminlte/myplugins/js/demo.js') }}"></script>
<!-- fullCalendar 2.2.5 -->
<!-- <script src="../plugins/moment/moment.min.js"></script> -->
<script src="{{ url('adminlte/myplugins/fullcalendar/main.min.js') }}"></script>
<script src="{{ url('adminlte/myplugins/fullcalendar-daygrid/main.min.js') }}"></script>
<script src="{{ url('adminlte/myplugins/fullcalendar-timegrid/main.min.js') }}"></script>
<script src="{{ url('adminlte/myplugins/fullcalendar-interaction/main.min.js') }}"></script>
<script src="{{ url('adminlte/myplugins/fullcalendar-bootstrap/main.min.js') }}"></script>
<!-- end of scripts from admin lte calender -->





<script>
    window._token = '{{ csrf_token() }}';
</script>

<script type="text/javascript">
  function myFunction() {
    var x = document.getElementById("quizDiv");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
</script>



@yield('javascript')