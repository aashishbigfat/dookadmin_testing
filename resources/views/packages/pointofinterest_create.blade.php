@extends('layouts.apps')
@section('headSection')
@section('title', 'Point of Interest Edit')
<link rel="stylesheet" href="{{asset('css/customCSS/departure_pointofinterest.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Edit POI</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">POI</li>
      </ol>
    </section>
    <section class="content">
      <div class="box">
        
        <div class="box-header with-border">
          <span class="btn btn-success" style="margin-top: -28px;">Total POIs <span style="color:#ffeb00">{{$totalpois}}</span></span>
          <form action="{{route('point_of_interest_edit')}}" method="get" style="display: inline-flex;">
          <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
              <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by POI, destination, country.." value="{{$keywords}}">
              
          </div>
          <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
        </form>
        </div>
        <div class="ItninerarListing" id="PoiListing">
          @include('packages/pointofinterest_list')
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}
    #map{height:180px}#searchInput{background-color:#fff;font-family:Roboto;font-size:15px;font-weight:300;margin-left:12px;padding:0 11px 0 13px;text-overflow:ellipsis;width:100%;height:35px;margin-top:0px;margin-left: 0px;}ul.dropdown-menu.inner{height:200px}.dropdown-menu.open.show{height:226px}.sussecmsg{font-size:16px;padding-left:10px;color:green}.error{color:red}.pac-container{z-index: 999999;}button.gm-control-active.gm-fullscreen-control {display: none;}
</style>
  @endsection
  @section('footerSection')
  <!-- Edit Poi -->
  <div class="modal fade" id="editPoiModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myPoiForms" enctype="multipart/form-data" id="myPoiForms">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 55%">
                <div class="modal-content">
                    <div class="modal-header col-md-12">
                          <div class="col-md-6"><h3 class="modal-title" id="exampleModalLabel">Edit Point of Interest</h3></div>
                         <div class="col-md-6"><button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i></button>
                      </div>
                    </div>
                  <div class="modal-body">
                  <div class="itinerary-setup m-t-20">
                  <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                    <div class="form-group" style="margin-bottom: 25px;">
                    <label>POI Name</label><br>
                      <input type="text" name="edit_poi" id="edit_poi" style="width: 100%;">
                      <input type="hidden" name="edit_id" id="edit_id">
                      <input type="hidden" name="ref_id" id="ref_id">
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                    <label>Address</label>
                    <div id="edit_address_poi"></div>
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                    <label>Description</label>
                    <div id="edit_description_poi"></div>
                    
                    </div>
                  </div>
                  <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                          <label>POI Image <span style="color: #9a191e">(W:464, H:260)</span></label><span class="validationError" id="add_image_error"></span>
                          <div class="input-group input-group-edit">
                              <span class="input-group-btn">
                                  <span class="btn btn-default btn-file btn-file-edit">
                                      Choose Image <input type="file" id="imgInpEdit" accept="image/jpeg, image/jpg, image/png">
                                  </span>
                              </span>
                              <input type="text" class="form-control" readonly="">
                              <input type="hidden" name="add_image_edit" id="image_base64edit">
                          </div>
                          
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-xl-4">

                      <img id="img-upload-edit" name="edit_default_image" style="height: 70px;">
                    </div>
                    <!-- Banner image -->
                    <div class="col-md-8 col-lg-8 col-xl-8" style="margin-top: 10px">
                      <div class="form-group">
                        <label>POI Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></label><span class="validationError" id="add_image_error_banner"></span>
                        <div class="input-group input-group-edit-banner">
                          <span class="input-group-btn">
                            <span class="btn btn-default btn-file btn-file-edit-banner">
                              Choose Image <input type="file" id="imgInpEditBanner" accept="image/jpeg, image/jpg, image/png">
                            </span>
                          </span>
                          <input type="text" class="form-control" readonly="">
                          <input type="hidden" name="add_image_edit_banner" id="image_base64edit_Banner">
                        </div> 
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-xl-4" style="margin-top: 10px">
                      <img id="img-upload-edit-banner" name="edit_default_image_banner" style="height: 70px;">
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
                        <!-- <img src="{{ asset('images/loader.gif') }}" id="edit_gif" style="width: 6%; visibility: hidden;"> -->
                        <span id="messages"></span>
                    </div>
                </div>
        </form>
    </div>

<script>
   $('#edit_experiences').select2({
        ajax: {
            url: "/get-experience-ajax",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.experience_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
  
  <script>
   $('.editPoi').on('click', function(e) {
    e.preventDefault();
    $('#editPoiModel').modal('show');

    var id = $(this).data('id');
    var poi_reff_id = $(this).data("poiId");
    var route_id = $(this).data("routeId");
    var poiNames = $(this).data('poiname');
    var address = $(this).data('address');
    var description = $(this).data('description');
    var destination_id = $(this).data("destinationid");
    var destination_name = $(this).data("destinationname");

    // These are now full signed URLs
    var image = $(this).data("image");
    var banner_image = $(this).data("bannerimage");

    // Set image sources directly
    $("#editPoiModel").find("img[name='edit_default_image']").attr('src', image);
    $("#editPoiModel").find("img[name='edit_default_image_banner']").attr('src', banner_image);

    // Set form values
    $("#edit_id").val(id);
    $("#edit_poi").val(poiNames);
    $("#ref_id").val(poi_reff_id);
    $("#route_id").val(route_id);

    // Set selected destination option
    $("#editPoiModel").find("select[name='edit_destinations'] option").prop("selected", false);
    $("#editPoiModel").find("select[name='edit_destinations'] option[value='" + destination_id + "']").prop("selected", true);

    // Set address and description
    $("#edit_address_poi").html('<textarea class="form-control" name="edit_address" id="edit_address" style="height: 100px">' + address + '</textarea>');
    $("#edit_description_poi").html('<textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px">' + description + '</textarea>');
});


</script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#update_Pois').click(function (e) {
                e.preventDefault();
                $(".crop_wait_edit").show();
                $(".crop_text_edit").hide();
                var edit_id = $('#ref_id').val();
                var formDatas = new FormData(document.getElementById('myPoiForms'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/departure/poi/update/' + edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#messages').html("<span class='sussecmsg'>Success!</span>");
                       location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <script>
   // var experienceID = [];
    $('.destinations').change(function(){
      var destinationID = $(this).find(':selected').attr('data-id');
      var destinationName = $(this).find(':selected').attr('data-destname');
      var countryreffID = $(this).find(':selected').attr('data-countryreffid');
      var countyName = $(this).find(':selected').attr('data-countryname');
      var regionid = $(this).find(':selected').attr('data-regionid');
      var geonameid = $(this).find(':selected').attr('data-geonameid');
      var region = $(this).find(':selected').attr('data-region');
      var lat = $(this).find(':selected').attr('data-lat');
      var long = $(this).find(':selected').attr('data-long');
      var iso3 = $(this).find(':selected').attr('data-iso');
      var fclass = $(this).find(':selected').attr('data-fclass');
      var fcode = $(this).find(':selected').attr('data-fcode');
      var routeId = $(this).find(':selected').attr('data-routeId');
      var dookdestid = $(this).find(':selected').attr('data-dookdestid');
      if(destinationID){
          $.ajax({ 
             type:"GET",
             url:"{{url('/destination-pois-ajax')}}?destination_id="+destinationID,
             success:function(res){
              $("#add_destination").val(destinationName);
              $("#add_reff_id").val(destinationID);
              $("#add_country").val(countyName);
              $("#add_country_reff_id").val(countryreffID);
              $("#add_regionid").val(regionid);
              $("#add_geonameid").val(geonameid);
              $("#add_dest_lat").val(lat);
              $("#add_dest_long").val(long);
              $("#add_iso_3").val(iso3);
              $("#add_dest_region").val(region);
              $("#add_fclass").val(fclass);
              $("#add_fcode").val(fcode);
              $("#add_route_id").val(routeId);
              $("#add_dook_dest_id").val(dookdestid);
              var data = res.poi;
              //alert(data);
              if(data && data.length > 0){
                $("#poiName").html('');
                  $.each(res.poi,function(key,value){   
                        var datas=JSON.stringify(value);                
                   $("#poiName").append("<option value='"+datas+"'>"+value.point_name+', '+destinationName+', '+value.country_name+"</option>");
                  });
             
              }else{
                 $("#poiName").empty();
              }
             },
             error:function(){
              alert('Something went wrong!');
             }
          });
      }else{
          $("#poiName").empty();
      }      
    });
  </script>
  <script>
    $("li a").each(function() {   
      //alert(this.href);
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
            url: '?page=' + page+'&keyword='+'<?php echo $keywords;?>',
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
  <script src="{{asset('js/customJS/poi.js')}}"></script>
  <script>
    //image base64
    $(document).ready( function() {
      $(document).on('change', '.btn-file :file', function() {
      var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [label]);
      });

      $('.btn-file :file').on('fileselect', function(event, label) {
          
          var input = $(this).parents('.input-group').find(':text'),
              log = label;
          
          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }
        
      });
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              
              reader.onload = function (e) {
                  $('#img-upload').attr('src', e.target.result);
                  $('#image_base64').val(e.target.result);
              }
              
              reader.readAsDataURL(input.files[0]);
          }
      }

      $("#imgInp").change(function(){
          readURL(this);
      });   
  });

  // update poi edit
  //image base64
    $(document).ready( function() {
      $(document).on('change', '.btn-file-edit :file', function() {
      var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [label]);
      });

      $('.btn-file-edit :file').on('fileselect', function(event, label) {
          
          var input = $(this).parents('.input-group-edit').find(':text'),
              log = label;
          
          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }
        
      });
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              
              reader.onload = function (e) {
                  $('#img-upload-edit').attr('src', e.target.result);
                  $('#image_base64edit').val(e.target.result);
              }
              
              reader.readAsDataURL(input.files[0]);
          }
      }

      $("#imgInpEdit").change(function(){
          readURL(this);
      });   
  });
    // Update POI Banner Image
    //image base64
    $(document).ready( function() {
      $(document).on('change', '.btn-file-edit-banner :file', function() {
      var input = $(this),
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [label]);
      });

      $('.btn-file-edit-banner :file').on('fileselect', function(event, label) {
          
          var input = $(this).parents('.input-group-edit-banner').find(':text'),
              log = label;
          
          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }
        
      });
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              
              reader.onload = function (e) {
                  $('#img-upload-edit-banner').attr('src', e.target.result);
                  $('#image_base64edit_Banner').val(e.target.result);
              }
              
              reader.readAsDataURL(input.files[0]);
          }
      }

      $("#imgInpEditBanner").change(function(){
          readURL(this);
      });   
    });
  </script>
  @endsection