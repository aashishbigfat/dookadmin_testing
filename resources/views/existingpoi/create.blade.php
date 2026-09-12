@extends('layouts.apps')
@section('headSection')
@section('title', 'Existing Remaining POIs')
<link rel="stylesheet" href="{{asset('css/customCSS/country.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Existing poi create</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('existingpoi_index')}}"><i class="fa fa-dashboard"></i> Existing POI List</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content" style="margin-top: 25px;">
      <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <form role="form" id="poiForm">
                <div class="form-group">
                <span class="validationError" id="destinations_error"></span>
                  <!-- <input type="text" id="destinations" class="form-control destinations" placeholder="Search pois.."> -->
                  <div class="autocomplete-items" style="display: none"></div>
                  <div id="droppoi">
                    <div class="row">
                      <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                        <label>Country</label> <span class="validationError" id="country_error"></span>
                          <select class="form-control country" name="country" id="country">
                            @foreach($country as $list)
                            <option value="{{$list->id}}">{{$list->country_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="form-group">
                          <label>POI Name</label> <span class="validationError" id="name_error"></span>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Enter poi name">
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-6">
                        <div class="form-group">
                          <label>Rating</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                          <input type="number" class="form-control" name="rating" id="rating" placeholder="Enter Rating" autocomplete="off">
                          <span class="validationError" id="rating_error"></span>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-6">
                        <div class="form-group">
                          <label>Type</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                          <input type="text" class="form-control" name="type" id="type" placeholder="Enter POI Type" autocomplete="off">
                          <span class="validationError" id="type_error"></span>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-6">
                        <div class="form-group">
                          <label>Latitude</label> <span class="validationError" id="latitide_error"></span>
                          <input type="text" class="form-control" name="latitide" id="latitide" placeholder="Enter latitide">
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <div class="form-group">
                          <label>Longitude</label> <span class="validationError" id="longitude_error"></span>
                          <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Enter Longitude">
                        </div>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Address</label> <span class="validationError" id="address_error"></span>
                          <textarea class="form-control" name="address" id="address" placeholder="Enter Poi Address.."></textarea>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Description</label> <span class="validationError" id="description_error"></span>
                          <textarea class="form-control" name="description" id="description" placeholder="Enter Poi description.."></textarea>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="form-group">
                          <label class="fbm_images" for="exampleInputFile">Image <span style="color: #9a191e">(W:464, H:260)</span></label> <span class="validationError" id="image_error"></span> 
                          <div class="input-group" style="margin-top:15px">
                            <input type="file" id="image" name="image" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12" id="uploaded_image" style="margin-top:15px">
                        <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="60"/ onclick="triggerImage()">
                    </div>
                      <div class="col-md-4 col-lg-4 col-sm-4">
                        <div class="form-group">
                          <label class="fbm_images" for="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></label> <span class="validationError" id="banner_error"></span> 
                          <div class="input-group" style="margin-top:15px">
                            <input type="file" id="banner_images" name="banner_image" accept="image/jpeg, image/jpg, image/png" onchange="readURLBanner(this);">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top:5px">
                        <img id="blahbanner" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="100" height="60"/ onclick="triggerImage()">
                      </div>
                    </div>
                      <div class="col-md-12 col-lg-12 col-sm-12" style="text-align: left;margin-top: 35px;">
                        <button class="btn btn-primary active" type="button" id="store_form">
                          <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                            <span class="crop_wait" style="display: none">            
                             Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                          </span>
                        </button>
                      </div>
                  </div>
                </div>
              </form>
              </div>
      </div>
    </section>
  </div>
  <!-- Edit Itinearay Modal-->
  <style type="text/css">
    span.select2-selection.select2-selection--single {
    padding: 3px;
}
  </style>
  @endsection
  @section('footerSection')
<script type="text/javascript">
  $(document).ready(function () {
      $('#store_form').click(function (e) {
          e.preventDefault();
          var formDatas = new FormData(document.getElementById('poiForm'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('existingpoi_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                  $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                  window.location = data.url;
              },
              errors: function () {

              }
          });
      });
  });

  $('.country').select2();

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#blah')
            .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#image').trigger('click');
    } 

    function readURLBanner(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#blahbanner')
            .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBanner(){
      $('#banner_images').trigger('click');
    } 
  // function fetch_from_pullit(data){
  //   $(".autocomplete-items").css('display','block');
  //       var html="";
  //       html+="<ul class='search-list'>";                
  //       let k=0;
  //       for(poi of data.poi){
  //         if(k<25){
  //            var poi_id=poi.id;
  //            var poi_name=poi.poiName;

  //            var latitude=poi.latitude;
  //            var longitude=poi.longitude;

  //            var address = poi.address;
  //            var description = poi.description;

  //            var poiType = poi.poiType;
  //            var phone = poi.phone;
  //            var website = poi.website;
  //            var openhours = poi.openhours;

  //            html+="<li onclick='initPoi("+poi.id+","+poi.latitude+","+poi.longitude+",&quot;"+poi.poiName+"&quot;,&quot;"+poi.address+"&quot;,&quot;"+poi.description+"&quot;,&quot;"+poi.poiType+"&quot;,&quot;"+poi.phone+"&quot;,&quot;"+poi.website+"&quot;,&quot;"+ poi.openhours+"&quot;,&quot><i style='margin-right:5px; color:#A9A9A9' class='fas fa-map-marker-alt'></i> <b style='color:#222'>"+poi.poiName+' ('+poi.poiName+')'+"</b></li>";
  //         }                       
  //         k++;
  //       }
  //       html+="</ul>";                  
  //         $(".autocomplete-items").html(html);
  //     }

  //   var poi_selected=[];
  //   function initPoi(id, Lat, Long, poi_name, address, description, type, phone, website, openhours) {

  //       poi_selected.push(
  //           {

  //               'id':id,
  //               'name':poi_name,
  //               'lat':Lat,
  //               'long':Long,
  //               'address':address,
  //               'id':description,
  //               'type':type,
  //               'phone':phone,
  //               'website':website,
  //               'openhours':openhours,
  //           }
  //         );
  //       $(".autocomplete-items").css('display','none');
  //       $(".pois").val('');
  //       $('#poiName').val(JSON.stringify(poi_selected));
  //       console.log(poi_selected);
  //       set_poi_html();     
  //   }
  </script>
  @endsection