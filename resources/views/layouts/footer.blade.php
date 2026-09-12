 <footer class="main-footer">
    <div class="pull-right hidden-xs">
     
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="{{ route('home') }}" style="color: #d71921">Dook International</a>.</strong> All rights reserved.
  </footer>

  <!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('bower_components/morris.js/morris.min.js')}}"></script>
<!-- Morris.js charts -->
<!-- <script src="{{asset('admin/bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('admin/bower_components/morris.js/morris.min.js')}}"></script> -->
<!-- Sparkline -->
<script src="{{asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<!-- <script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script> -->
<!-- jQuery Knob Chart -->
<script src="{{asset('bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
<!-- <script src="{{asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script> -->
<!-- datepicker -->
<script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{asset('dist/js/pages/dashboard.js')}}"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
<script src="{{asset('dist/js/demo.js')}}"></script>

{{-- datatable --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function() {
		setTimeout(function(){
			$(".content-wrapper").css("min-height","1100px");
   		},1000);
	    
	});
</script>
<script>
  $('.rsv_dollor').on('click', function() {
    $('#rupee-dollor').modal('show');
    var id = $(this).data('id');
    var usd = $(this).data('usd');
    var inr = $(this).data('inr');

    $("#edit_id_rs").val(id);
    $("#rupies").val(inr);
    $("#dollors").val(usd);
  });
</script>

<script type="text/javascript">
    $(document).ready(function () {
    $('#update_inr_usd').click(function (e) {
      e.preventDefault();
      $(".crop_wait_edit_rs").show();
      $(".crop_text_edit_rs").hide();
      var edit_id_rs = $("#edit_id_rs").val();
      
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: '/rupee_dollar/' + edit_id_rs,
        data: $('#myFormrupeeDollor').serialize(),
        success: function (data) {
          $('#messages_rs').html("<span class='sussecmsg'>Updated.</span>");
          location.reload();
        },
        errors: function () {
          $(".crop_wait_edit_rs").hide();
          $(".crop_text_edit_rs").show();
        }
      });
    });
  });   
  </script>
@section('footerSection')
  
@show
</body>
</html>
