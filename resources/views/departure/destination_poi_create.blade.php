@extends('layouts.apps')
@section('headSection')
@section('title', 'Departure | Point of Interest')
<link rel="stylesheet" href="{{asset('css/customCSS/departure_pointofinterest.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Add POI</h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('departures')}}">Departures</a></li>
      <li class="active">POI</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="steps clearfix text-center">
            @include('layouts/itinerary_menu')
          </div>
        </div>
      </div>
      <form role="form" id="poiForm">
        @csrf
        <div class="box-body">
          <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xl-4 sss">
            <div class="form-group edit_dest">
              <label>Destinations</label> <span class="validationError" id="destinations_error"></span>
              <select class="form-control destinations" name="destinations" id="destinations">
                <option value="">Select destination..</option>
                @foreach($destinations as $destination)
                <option value="{{$destination->id}}" data-dookdestid="{{$destination->id}}"
                  data-id="{{$destination->reference_id}}" data-countryreffid="{{$destination->country_reference_id}}"
                  data-destname="{{$destination->dest_name}}" data-countryname="{{$destination->country_name}}"
                  data-region="{{$destination->region}}" data-lat="{{$destination->latitude}}"
                  data-long="{{$destination->longitude}}" data-fclass="{{$destination->feature_class}}"
                  data-fcode="{{$destination->feature_code}}" data-iso="{{$destination->country_iso_3}}"
                  data-routeId="{{ request()->route('id') }}">{{$destination->dest_name}}
                  ({{$destination->country_name}})</option>
                @endforeach

              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label>Point Of Interest</label> <span class="validationError" id="poiName_error"></span>
              <!-- <input type="hidden" name="poiName" id="poiName" class="form-control poiName"> -->
              <select class="form-control poiName" name="poiName[]" id="poiName" multiple="">
              </select>
              <!--  <input type="text" id="pois" class="form-control pois" placeholder="Search point of interests..">
                  <div class="autocomplete-items" style="display: none"></div>
                  <div id="dropdest"> -->

              <!-- </div> -->
            </div>
          </div>
          <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
            <label>If not in select box list?</label>
            <a class="btn btn-default add_more_poi" data-toggle="modal" onclick="loadMap()">Add More</a>
          </div>
          <div class="col-md-4 col-lg-4 col-xl-8 col-sm-12 col-xs-12">
            <div class="form-group">
              <label>Experiences</label> <span class="validationError" id="experiences_error"></span>
              <select class="form-control experiences" name="experiences[]" id="experiences" multiple="">
              </select>
            </div>
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
            <button class="btn btn-primary active" type="button" id="store_form">
              <span class="crop_text"><i class="fa fa-save"></i> Save</span>
              <span class="crop_wait" style="display: none">
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;"> -->
            <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
          </div>
        </div>
      </form>
    </div>
    <div class="box">
      <div class="box-header with-border">
        <h4>Destination POIs List</h4>
      </div>
      <div class="ItninerarListing" id="PoiListing">
        @include('departure/destination_poi_list')
      </div>

    </div>
  </section>
</div>
<style type="text/css">
  table.loading>tbody {
    position: relative
  }

  table.loading>tbody:after {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, .1);
    background-image:url("{{ asset('images/loaders.gif') }}");
    background-position: center;
    background-repeat: no-repeat;
    background-size: 65px 65px;
    content: ""
  }

  #map {
    height: 180px
  }

  #searchInput {
    background-color: #fff;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    margin-left: 12px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 100%;
    height: 35px;
    margin-top: 0px;
    margin-left: 0px;
  }

  ul.dropdown-menu.inner {
    height: 200px
  }

  .dropdown-menu.open.show {
    height: 226px
  }

  .sussecmsg {
    font-size: 16px;
    padding-left: 10px;
    color: green
  }

  .error {
    color: red
  }

  .pac-container {
    z-index: 999999;
  }

  button.gm-control-active.gm-fullscreen-control {
    display: none;
  }
</style>
@endsection
@section('footerSection')
<div class="modal fade" id="addMorePois" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <form method="post" name="myForm" enctype="multipart/form-data" id="addMorePoiForm">
    @csrf
    <div class="modal-dialog modal-xl" role="document" style="width: 55%">
      <div class="modal-content">
        <div class="modal-header col-md-12">
          <div class="col-md-6">
            <h5 class="modal-title" id="exampleModalLabel">Add More Poi</h5>
          </div>
          <div class="col-md-6"><button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="itinerary-setup m-t-20">
            <div class="days" style="margin:-10px">
              <div class="col-xl-12 col-lg-12">
                <div class="kt-portlet kt-portlet--height-fluid">
                  <input id="searchInput" class="controls" type="text" placeholder="Enter a Point of Interest Name">
                  <div id="map"></div>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <input type="hidden" class="form-control" name="dep_type" id="dep_type" value="package">
                  <input type="hidden" class="form-control" name="add_dook_dest_id" id="add_dook_dest_id">
                  <input type="hidden" class="form-control" name="add_route_id" id="add_route_id">
                  <input type="hidden" class="form-control" name="add_place_id" id="add_place_id">
                  <input type="hidden" class="form-control" name="add_poi_type" id="add_poi_type">
                  <input type="hidden" class="form-control" name="add_hours" id="add_hours">
                  <!-- <input type="hidden" class="form-control" name="add_lat" id="add_lat">
                        <input type="hidden" class="form-control" name="add_long" id="add_long"> -->
                  <input type="hidden" class="form-control" name="add_rating" id="add_rating">
                  <input type="hidden" class="form-control" name="add_reviews" id="add_reviews">
                  <input type="hidden" class="form-control" name="add_web_url" id="add_web_url">
                  <input type="hidden" class="form-control" name="add_poi_url" id="add_poi_url">
                  <input type="hidden" class="form-control" name="add_phone" id="add_phone">
                  <input type="hidden" class="form-control" name="add_dest_lat" id="add_dest_lat">
                  <input type="hidden" class="form-control" name="add_dest_long" id="add_dest_long">
                  <input type="hidden" class="form-control" name="add_iso_3" id="add_iso_3">
                  <input type="hidden" class="form-control" name="add_dest_region" id="add_dest_region">
                  <input type="hidden" class="form-control" name="add_fclass" id="add_fclass">
                  <input type="hidden" class="form-control" name="add_fcode" id="add_fcode">
                  <input type="hidden" class="form-control" name="add_regionid" id="add_regionid">
                  <input type="hidden" class="form-control" name="add_geonameid" id="add_geonameid">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>POI Name</label> <span class="validationError" id="add_poi_error"></span>
                  <input type="text" class="form-control" name="add_poi" id="add_poi_name">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Destination</label> <span class="validationError" id="add_destination_error"></span>
                  <input type="text" class="form-control" name="add_destination_name" id="add_destination">
                  <input type="hidden" class="form-control" name="add_destination" id="add_reff_id">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Country</label> <span class="validationError" id="add_country_error"></span>
                  <input type="text" class="form-control" name="add_country_name" id="add_country">
                  <input type="hidden" class="form-control" name="add_country" id="add_country_reff_id">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Address</label><span class="validationError" id="add_address_error"></span>
                  <textarea class="form-control" name="add_address" id="add_address"></textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Latitude</label> <span class="validationError" id="add_poi_error"></span>
                  <input type="text" class="form-control" name="add_lat" id="add_lat">
                </div>

              </div>
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Longitude</label> <span class="validationError" id="add_destination_error"></span>
                  <input type="text" class="form-control" name="add_long" id="add_long">
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>Description</label><span class="validationError" id="add_description_error"></span>
                  <textarea class="form-control" name="add_description" id="add_description"
                    style="height: 80px"></textarea>
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8 col-sm-12 col-xs-12 sss">
                <div class="form-group">
                  <label>POI Image</label><span class="validationError" id="add_image_error"></span>
                  <div class="input-group">
                    <span class="input-group-btn">
                      <span class="btn btn-default btn-file">
                        Choose Image <input type="file" id="imgInp">
                      </span>
                    </span>
                    <input type="text" class="form-control" readonly>
                    <input type="hidden" name="add_image" id="image_base64">
                  </div>

                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                <img id='img-upload' / style="width: 45%">
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <div class="text-left">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i>
            Cancel</button>
          <button type="submit" class="btn btn-primary" id="add_poi_save">
            <span class="crop_text_more"><i class="fa fa-save"></i> Update</span>
            <span class="crop_wait_more" style="display: none">
              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
            </span>
          </button>
          <!-- <img src="{{ asset('images/loader.gif') }}" id="add_gif" style="width: 4%; visibility: hidden;"> -->
          <span id="more_messages"></span>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- Edit Poi -->
<div class="modal fade" id="editPoiModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <form method="post" name="myPoiForms" enctype="multipart/form-data" id="myPoiForms">
    @csrf
    <div class="modal-dialog modal-xl" role="document" style="width: 55%">
      <div class="modal-content">
        <div class="modal-header col-md-12">
          <div class="col-md-6">
            <h3 class="modal-title" id="exampleModalLabel">Edit Point of Interest</h3>
          </div>
          <div class="col-md-6"><button type="button" class="close text-right" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="itinerary-setup m-t-20">
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
              <div class="form-group edit_dest">
                <label>Destinations</label> <br>
                <select class="form-control edit_destinations" name="edit_destinations" id="edit_destinations">
                  @foreach($destinations as $destination)
                  <option value="{{$destination->id}}">{{$destination->dest_name}} ({{$destination->country_name}})
                  </option>
                  @endforeach

                </select>
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
              <div class="form-group" style="margin-bottom: 25px;">
                <label>POI Name</label><br>
                <input type="text" name="edit_poi" id="edit_poi" style="width: 100%;">
                <input type="hidden" name="edit_id" id="edit_id">
                <input type="hidden" name="ref_id" id="ref_id">
                <input type="hidden" name="route_id" id="route_id">
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Experiences</label><br>
                <select class="form-control edit_experiences" name="edit_experiences[]" id="edit_experiences"
                  multiple="">
                </select>
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
                <label>POI Image <span style="color: #9a191e">(W:464, H:260)</span></label><span class="validationError"
                  id="add_image_error"></span>
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
                <label>POI Banner Image <span style="color: #9a191e">(W:1920, H:1080)</span></label><span
                  class="validationError" id="add_image_error_banner"></span>
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i>
            Close</button>
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

<script type="text/javascript">
  (function() {
      var cors_api_host = 'cors-anywhere.herokuapp.com';
      var cors_api_url = 'https://' + cors_api_host + '/';
      var slice = [].slice;
      var origin = window.location.protocol + '//' + window.location.host;
      var open = XMLHttpRequest.prototype.open;
      XMLHttpRequest.prototype.open = function() {
          var args = slice.call(arguments);
          var targetOrigin = /^https?:\/\/([^\/]+)/i.exec(args[1]);
          if (targetOrigin && targetOrigin[0].toLowerCase() !== origin &&
              targetOrigin[1] !== cors_api_host) {
              args[1] = cors_api_url + args[1];
          }
          return open.apply(this, args);
      };
    })();
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                //$('#gif').show();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var destinationName = $('#destinations').val();
                if (destinationName == "") {
                    $("span#destinations_error").html('This field is required!');
                    $("select#destinations").focus();
                    return false;
                }
                // var poi_name = $('#poiName').val();
                // if (poi_name == "") {
                //   $("span#destinations_error").hide()
                //     $("span#poiName_error").html('This field is required!');
                //     $("select#poiName").focus();
                //     return false;
                // }

                var experience = $('#experiences').val();
                if (experience == "") {
                  $("span#poiName_error").hide()
                    $("span#experiences_error").html('This field is required!');
                    $("select#experiences").focus();
                    return false;
                }

                //$('#gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('departure_poi_store',request()->route('id')) }}",
                    data: $('#poiForm').serialize(),
                    success: function (data) {
                      console.log(data);
                        //$('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location = data.url;
                        //location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
</script>
<script type="text/javascript">
  $(".disablepoi").click(function () {
      
      var status = $(this).data("status");
      var flag = status?'inactive':'active';
      if (confirm("Are you sure you want to "+flag+" this POIs?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/departure-poi-disable/' + id,
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    });
</script>
<script type="text/javascript">
  $(".deleteExp").click(function () {
      if (confirm("Are you sure you want to delete this Destinationss?"))
      var id = $(this).data("id");
      var route_id = $(this).attr("data-routeId")
      var token = $("meta[name='csrf-token']").attr("content"); 
      if(id){
        $.ajax(
        {
          url: '/packages_poi_delete',
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
              "route_id":route_id,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    });
</script>
<!-- Destination Experience Related data -->
<script>
  $('#experiences').select2({
            placeholder: 'Select Experience(s)',
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
  //$('#edit_destinations').select2();
    //$('#edit_experiences').select2();
     $('.editPoi').on('click', function() {
      $('#editPoiModel').modal('show');
            var id = $(this).data('id');
            var poi_reff_id = $(this).attr("data-poiId");
            var route_id = $(this).attr("data-routeId");
            var poiNames = $(this).data('poiname');

            var address = $(this).data('address');
//console.log(address);
            var description = $(this).data('description');

            var destination_id = $(this).data("destinationid");
            //var dest_id = JSON.parse(destination_id);
//console.log(destination_id);
            var destination_name = $(this).data("destinationname");
            //var dest_name = JSON.parse(destination_name);

            var exp_id = $(this).attr("data-expid");
            var experiences_id = JSON.parse(exp_id);

            var exp_name = $(this).attr("data-expname");
            var experiences_name = JSON.parse(exp_name);

            var image = $(this).attr("data-image");
            var basepath = "<?php echo $urlS3; ?>";
            var imagePath = basepath+image;
            var banner_image = $(this).attr("data-bannerimage");
            var bannerImagePath = basepath+banner_image;
            
            $("#editPoiModel").find("img[name='edit_default_image']").attr('src', imagePath);
            $("#editPoiModel").find("img[name='edit_default_image_banner']").attr('src', bannerImagePath);
            $("#edit_id").val(id);
            $("#edit_poi").val(poiNames);
            $("#ref_id").val(poi_reff_id);
            $("#route_id").val(route_id);
            $("#editPoiModel").find("select[name='edit_destinations'] option[value='"+destination_id+"']").attr('selected', 'selected');

             $("#edit_address_poi").html('<textarea class="form-control" name="edit_address" id="edit_address" style="height: 100px">'+address+'</textarea>');
            $("#edit_description_poi").html('<textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px">'+description+'</textarea>');

            // var datas = [];
            // $('#edit_destinations').val('').trigger('change');

            // for (var i = 0; i < dest_id.length; i++) {
            //     var $select = $("#edit_destinations");
            //     var items = {id: dest_id[i], text: dest_name[i]};
            //     //console.log(items);
            //     var datas = $select.val() || [];
            //     $(items).each(function () {
            //         if (!$select.find("option[value='" + this.id + "']").length) {
            //             $select.append(new Option(this.text, this.id, true, true));
            //         }
            //         datas.push(this.id);
            //     });
            //     $select.val(datas).trigger('change');
            // }       

            var dataExp = [];
            $('#edit_experiences').val('').trigger('change');

            for (var i = 0; i < experiences_id.length; i++) {
                var $select = $("#edit_experiences");
                var items = {id: experiences_id[i], text: experiences_name[i]};
                //console.log(items);
                var dataExp = $select.val() || [];
                $(items).each(function () {
                    if (!$select.find("option[value='" + this.id + "']").length) {
                        $select.append(new Option(this.text, this.id, true, true));
                    }
                    dataExp.push(this.id);
                });
                $select.val(dataExp).trigger('change');
            }         
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
                        //$('#edit_gif').hide();
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
                  //$("#poiName").val(activity_selected);
             
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
    // var activity_selected=[];
    // $('#activities').change(function(){
    //   var activtyId = $(this).val(); 
    //   //alert(destinationID);
    //   if(activtyId){
    //     activity_selected=activtyId;

    //   }
    // });
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
<script src="{{asset('js/customJS/poi.js')}}"></script>
<script>
  $('.add_more_poi').on('click', function() {
      var dest = $('#add_destination').val();
      if(dest == ""){
        alert("Please select destination befor to click add more button,!");
        return false;
      }
      $('#addMorePois').modal('show');
    });
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
<script>
  function loadMap(){
    var lat = $('#destinations').find(':selected').attr('data-lat');
    var long = $('#destinations').find(':selected').attr('data-long');
    initMap(parseInt(lat), parseInt(long));
  }
  function initMap(Lat, Long) {
    var map = new google.maps.Map(document.getElementById('map'), {
      //center: {lat: 28.644800, lng: 77.216721},
      center: {lat: Lat, lng: Long},
      zoom: 5
    });
    var input = document.getElementById('searchInput');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -20)
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    
        var address = '';
        if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
        // Location details
        // for (var i = 0; i < place.address_components.length; i++) {
           
        //     if(place.address_components[i].types[0] == 'locality'){
        //         $('#desti').val(place.address_components[i].long_name);
        //     }
        //     if(place.address_components[i].types[0] == 'country'){
        //         $('#country').val(place.address_components[i].long_name);
        //     }
           
        // }
        var pointn = place.name;
        var Lat = place.geometry.location.lat();
        var Long = place.geometry.location.lng();
        var address = place.formatted_address;
        var placeid = place.place_id;

        $.ajax({
            type:"get",
            url: 'https://cors-anywhere.herokuapp.com/'+'https://maps.googleapis.com/maps/api/place/details/json?placeid='+placeid+'&fields=name,rating,url,address_component,types,website,geometry,photo,formatted_phone_number,international_phone_number,icon,price_level,user_ratings_total,opening_hours/weekday_text&key={{ config('services.google_maps.key') }}',
            crossDomain: false,

            success: function(data) {
              console.log(data)
              $('#add_poi_name').val(pointn);
              $('#add_address').val(address);
              $('#add_lat').val(Lat);
              $('#add_long').val(Long);
              $('#add_place_id').val(placeid);
              $('#add_rating').val(data.result.rating);
              $('#add_poi_url').val(data.result.url);
              $('#add_reviews').val(data.result.user_ratings_total);
              $('#add_poi_type').val(data.result.types);
              if(data.result.opening_hours != undefined && data.result.opening_hours.weekday_text != undefined){
                $('#add_hours').val(data.result.opening_hours.weekday_text);
              }
              
              if(data.result.website != undefined){
                $('#add_web_url').val(data.result.website);
              }
              
              if(data.result.international_phone_number != undefined){
                $('#add_phone').val(data.result.international_phone_number);
              }
              
            }
        });
    });
}   
</script>
<script
  src="https://maps.googleapis.com/maps/api/js?libraries=places&language=en&key={{ config('services.google_maps.key') }}">
</script>


<script type="text/javascript">
  $(document).ready(function () {

            $('#add_poi_save').click(function (e) {
                e.preventDefault();
                //$('#add_gif').show();
                $(".crop_wait_more").show();
                $(".crop_text_more").hide();
                var add_poi_name = $('#add_poi_name').val();
                //console.log(add_poi_name);
                  if (add_poi_name == "") {
                  $("span#add_poi_error").html('This field is required!');
                  $("input#add_poi_name").focus();
                  return false;
                }
                var destinationName = $('#add_reff_id').val();
                  if (destinationName == "") {
                  $("span#add_poi").hide();
                  $("span#add_destination_error").html('This field is required!');
                  $("input#add_destination").focus();
                  return false;
                }
                var add_country = $('#add_country_reff_id').val();
                  if (add_country == "") {
                  $("span#add_destination_error").hide();
                  $("span#add_country_error").html('This field is required!');
                  $("input#add_country").focus();
                  return false;
                }
                var add_address = $('#add_address').val();
                if (add_address == "") {
                  $("span#add_country_error").hide();
                  $("span#add_address_error").html('This field is required!');
                  $("select#add_address").focus();
                  return false;
                }
                // var add_description = $('#add_description').val();
                // if (add_description == "") {
                //   $("span#add_address_error").hide();
                //     $("span#add_description_error").html('This field is required!');
                //     $("input#add_description").focus();
                //     return false;
                // }
                var imgInp = $('#imgInp').val();
                if (imgInp == "") {
                  //$("span#add_description_error").hide();
                  $("span#add_image_error ").html('This field is required!');
                  $("input#imgInp").focus();
                  return false;
                }

                //$('#add_gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('addMorePoiForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{route('add_more_pois')}}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      console.log(data);
                      $(".crop_wait_more").hide();
                      $(".crop_text_more").show();
                      $("#more_messages").hide();
                      $('#destinations').trigger('change');
                      $('#more_messages').html("<span class='sussecmsg'>Success!</span>");
                      //document.getElementById("addMorePoiForm").reset();
                      $("#img-upload").attr('src', '');
                      //$('#addMorePois').modal('hide');
                        //window.location = data.url;
                        //location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
</script>
@endsection