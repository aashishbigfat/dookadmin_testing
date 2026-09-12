@extends('layouts.apps')
@section('headSection')
@section('title', 'Departure | Date Wisw Group Tours')
<link rel="stylesheet" href="{{asset('css/customCSS/departure_pointofinterest.css')}}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Date Wise Group Tours List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('group_packages')}}">Group Tours</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/group_itinerary_menu')
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <div class="box-header with-border">
         <span><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Create Group Tour Date Wise</button> </span><span class="btn btn-success">Total Dates <span style="color:#ffeb00"> {{$total}}</span></span>
        </div>
        <div class="ItninerarListing" id="PoiListing">
          @include('grouppackages/departure_date_data')
        </div>
      </div>
    </section>
  </div>
  
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}
    #map{height:180px}#searchInput{background-color:#fff;font-family:Roboto;font-size:15px;font-weight:300;margin-left:12px;padding:0 11px 0 13px;text-overflow:ellipsis;width:100%;height:35px;margin-top:0px;margin-left: 0px;}ul.dropdown-menu.inner{height:200px}.dropdown-menu.open.show{height:226px}.sussecmsg{font-size:16px;padding-left:10px;color:green}.error{color:red}.pac-container{z-index: 999999;}button.gm-control-active.gm-fullscreen-control {display: none;}.displayInline>h3{display: inline-block;margin-right: 50px;}h3#edit_title {color: red;}h3#edit_date {color: royalblue;}.padding10{padding-left: 10px !important;display: inline-block;padding: 5px;}div#ui-datepicker-div{z-index:999999999 !important;}.ui-datepicker-buttonpane.ui-widget-content {display: none;}
</style>
  @endsection
  @section('footerSection')
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" style="width: 55%">

    <form method="post" name="createMyDateForms" enctype="multipart/form-data" id="createMyDateForms">
      @csrf
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Group Date</h4>
      </div>
      <div class="modal-body">
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Price INR</label><span class="validationError" id="create_price_inr_error"></span>
                <input type="text" class="form-control" name="create_price_inr" id="create_price_inr">
                <input type="hidden" class="form-control" name="create_id" id="create_id" value="{{request()->route('id')}}">
              </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Price USD</label><span class="validationError" id="create_price_usd_error"></span>
                <input type="text" class="form-control" name="create_price_usd" id="create_price_usd">
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6">
              <div class="form-group">
                <label>Travel Date</label> <span class="validationError" id="create_date_error"></span>
                <div class="input-group date">
                  <input type="text" class="form-control pull-right" name="create_date" id="create_date" autocomplete="off">
                  <div class="input-group-addon">
                      <i class="fa fa-calendar start-calendar"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12 col-lg-12" style="margin-top: 20px">
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="create_description" id="create_description" rows="6"></textarea>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="create_group">
          <span class="crop_text_create"><i class="fa fa-save"></i> Create</span>
          <span class="crop_wait_create" style="display: none">         
             Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
          </span>
        </button>
        <span id="create_messages"></span>
      </div>
    </div>
  </form>
  </div>
  <div class="modal fade" id="editDateModel" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myDateForms" enctype="multipart/form-data" id="myDateForms">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 55%">
                <div class="modal-content">
                    <div class="modal-header col-md-12">
                         <div class="col-md-10 displayInline" style="margin-left: -15px;"><h3 class="modal-title" id="exampleModalLabel">Edit Group Date</h3> <h3 id="edit_title"></h3><h3 id="dookid"></h3></div>
                         <div class="col-md-2" style="margin-left: 15px;"><button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i></button>
                      </div>
                    </div>
                  <div class="modal-body">
                  <div class="itinerary-setup m-t-20">
                  <div class="row">
                  
                  <div class="col-md-3 col-lg-3 col-xl-3">
                    <div class="form-group">
                      <label>Price INR</label>
                      <input type="text" class="form-control" name="edit_price_inr" id="edit_price_inr">
                      <input type="hidden" class="form-control" name="edit_id" id="edit_id">
                    </div>
                  </div>
                  <div class="col-md-3 col-lg-3 col-xl-3">
                    <div class="form-group">
                      <label>Price USD</label>
                      <input type="text" class="form-control" name="edit_price_usd" id="edit_price_usd">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6 col-xl-6">
                    <div class="form-group">
                      <label>Start Date</label>
                      <div class="input-group date">
                        <input type="text" class="form-control pull-right" name="edit_date" id="edit_date" autocomplete="off">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar start-calendars"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12" style="margin-top: 20px">
                    <div class="form-group">
                      <label>Description</label>
                      <div id="edit_description_date"></div>
                    </div>
                  </div>
                  </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-primary" id="update_Pois">
                      <span class="crop_text_edit"><i class="fa fa-edit"></i> Update</span>
                      <span class="crop_wait_edit" style="display: none">         
                         Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                      </span>
                    </button>
                    <span id="messages"></span>
                </div>
            </div>
        </form>
  </div>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
    $( document ).ready(function() {
      $('#create_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd-M-yy',
        minDate: 0,
      });
    });
    $('.start-calendar').click(function () {
      $("#create_date").focus();
    });

    //edit

    $( document ).ready(function() {
      $('#edit_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd-M-yy',
        minDate: 0,
      });
    });
    $('.start-calendars').click(function () {
      $("#edit_date").focus();
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#create_group').click(function (e) {
        e.preventDefault();
        //$('#edit_gif').show();
        $(".crop_wait_create").show();
        $(".crop_text_create").hide();

        var create_date = $('#create_date').val();
        if (create_date == "") {
          $(".crop_wait_create").hide();
          $(".crop_text_create").show();
          $("span#create_date_error").html('This field is required!');
          $("input#create_date").focus();
          return false;
        }
        // var create_description = $('#create_description').val();
        // if (create_description == "") {
        //     $("span#create_date_error").hide();
        //     $("span#create_description_error").html('This field is required!');
        //     $("input#create_description").focus();
        //     return false;
        // }
        //$('#edit_gif').css('visibility', 'visible');
        var formDatas = new FormData(document.getElementById('createMyDateForms'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{ route('group_date_create',request()->route('id')) }}",
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
                //$('#edit_gif').hide();
              $('#create_messages').html("<span class='sussecmsg'>Success!</span>");
                window.location.reload();
            },
            errors: function () {

            }
        });
      });
    });
</script>
  <script>
    //$('#edit_destinations').select2();
    //$('#edit_experiences').select2();
     $('.editDateDeparture').on('click', function() {
      	$('#editDateModel').modal('show');
        var id = $(this).data('id');
        var title = $(this).attr("data-title");
        var date = $(this).attr("data-date");
        var dookid = $(this).data('dookid');

        var priceinr = $(this).data('priceinr');
        var priceusd = $(this).data('priceusd');
        var description = $(this).data('description');

        $("#edit_description_date").html('<textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px">'+description+'</textarea>');    
        
        $("#edit_id").val(id);
        $("#edit_title").html(title);
        $("#edit_date").val(date);
        $("#edit_price_inr").val(priceinr);
        $("#edit_price_usd").val(priceusd);
        $("#dookid").val(dookid);
        
    });

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#update_Pois').click(function (e) {
            e.preventDefault();
            //$('#edit_gif').show();
            $(".crop_wait_edit").show();
            $(".crop_text_edit").hide();
            var edit_id = $('#edit_id').val();

            //$('#edit_gif').css('visibility', 'visible');
            var formDatas = new FormData(document.getElementById('myDateForms'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: '/group_tours_dates/update/' + edit_id,
                data: formDatas,
                contentType: false,
                processData: false,
                success: function (data) {
                    //$('#edit_gif').hide();
                	$('#messages').html("<span class='sussecmsg'>Success!</span>");
                   	window.location.reload();
                },
                errors: function () {

                }
            });
        });
    });
</script>
  <script>
    $("li a").each(function() { 
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
    var destpoi = $('#destinations').val();
  </script>
  <script>
      //Ajax Pagination
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
    
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
          $('#DeparturePoisData').addClass('loading');
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
  
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
  
            getData(page);
        });
  
    });
  
    function getData(page){
        $.ajax(
        {
            url: '?page=' + page,
            type: "get",
            datatype: "html"
        }).done(function(data){
          $('#DeparturePoisData').removeClass('loading');
            $("#PoiListing").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
    </script>
 
  @endsection