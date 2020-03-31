<!DOCTYPE html>
<html>
<head>
    @include('includes.loggedinhead')
</head>

<body class="hold-transition skin-black sidebar-mini">
    <div class="wrapper">
      @include('includes.loggedintop')
<?php 
$user = Auth::user();
//echo "<pre>"; print_r($user->avatar); echo "</pre>";   die;?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
            <?php
                $avatar_path = Config::get('constants.user_avatar_path');
                $path = !empty($user->avatar) ? $avatar_path . $user->avatar : 'dist/img/avatar.png';
            ?>
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{ url($path) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p class="profile-user-first-name">{{ $user->first_name }}</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            @if($user->hasRole('Admin'))
              @include('includes.leftnavadmin')
            @endif
            
            @if($user->hasRole('Distributor'))
              @include('includes.leftnavuser')
            @endif
            @if($user->hasRole('Shop'))
              @include('includes.leftnavshop')
            @endif
            <!--@include('includes.leftnavcore')-->
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

       @yield('content')


	<!--  @include('includes.controlsidebar') -->
        @include('includes.loggedinfooter')
    </div><!-- ./wrapper -->

<script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <!-- <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- include modals -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog"  role="document">
            <div class="modal-content load_modal"></div>
        </div>
    </div>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
    
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{asset('plugins/fastclick/fastclick.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/app.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js') }}"></script>
    <!-- CK Editor 
    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>-->
    <script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script src="{{ asset('js/custom.js?v=1.0') }}"></script>
    <script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        //$.fn.modal.Constructor.prototype.enforceFocus = function() {};
        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
        //Datemask2 mm/dd/yyyy
        $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
        //Money Euro
        $("[data-mask]").inputmask();
$('.datetimepicker1').datetimepicker();
        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
              startDate: moment().subtract(29, 'days'),
              endDate: moment()
            },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        );

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false
        });
      });
    </script>
        <script>
      $(function () {
        $(".dataTable").DataTable();
        $(".fullDetailTable").DataTable({
          "order":[5,'asc'],
          "pageLength": 50,
        });
        $('.fullDetailTable2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
          "pageLength": 50,
        });
      });
    </script>
    <script>
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replaceClass = 'editor';

        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
    </script>
    <script>
  $(function () {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    });
  </script>
    </body>
</html>
