@extends('layouts.apps')
@section('headSection')
@section('title', 'Home Sliders')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Home Slider</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Home Slider</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">

      <div class="row">
        <form role="form" id="depInfocus">
          @csrf
            <div class="box-body" style="margin-top: -23px;">

              <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                 <div class="form-group edit_dest">
                     <label>Slider Title</label> <span class="validationError" id="slider_error"></span><br>
                     <input class="form-control" type="text" name="slider_title" id="slider_title">
                   </div>
                 </div>

               <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                 <div class="form-group edit_dest">
                   <label>Slider Subtitle</label> <span class="validationError" id="slider_sub_error"></span><br>
                   <input class="form-control" type="text" name="slider_sub_title" id="slider_sub_title">
                 </div>
               </div>

              <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label style="margin-bottom: 10px">Select or Search Countries</label><span class="validationError" id="country_error"></span>
                    <select class="form-control country" name="country" id="country">
                      <option value="">Select Country</option>
                      @foreach($countries as $value)
                        <option value="{{$value->id}}">{{$value->country_name}}</option>
                      @endforeach
                    </select>
                  </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label style="margin-bottom: 10px">Select or Search Departures</label> <span class="validationError" id="departures_error"></span>
                    <select class="form-control departures" name="departures[]" id="departures" multiple="">
                    </select>
                  </div>
              </div>
              <!-- <div class="col-md-12 col-lg-12 col-sm-12">
               <h3>Image</h3>
               <hr style="border-bottom: 2px solid #777">
             </div> -->
             <div class="col-md-4 col-lg-4 col-sm-12" style="margin-top: 15px">
               <div class="form-group">
                <label style="margin-bottom: 15px">Slider Image</label> <span class="validationError" id="image_error"></span>
                <div class="input-group">
                   <input type="file" name="slider_image" id="uploadFile" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                 <button type="button" class="btn btn-primary">Choose Image</button>
               </div>
               </div>
             </div>
             <div class="col-md-4 col-lg-4 col-sm-12" style="margin-top: 15px">
                 <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
             </div>
              <div class="col-md-12 col-lg-12 col-sm-12 text-left" style="margin-top: 24px;">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Add Slider</span>
                    <span class="crop_wait" style="display: none">                      
                     Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                  </span>
                </button>
                <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden; display: inline-block;"> -->
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div>
            </div>
        </form>
      </div>
      <div class="box" style="margin-top: 25px">
        <div class="box-header with-border">
          <span style="font-size: 16px;">Sliders List
        </div>
        <div class="TodDestListing" id="TodDestListing">
            @include('landinghome/data_list')
        </div>
      </div>
    </section>
  </div>

  <!-- Edit Destination -->
  <div class="modal fade" id="editModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 65%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Home Slider</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Title</label> <span class="validationError" id="edit_title_error"></span>
                    <input type="text" class="form-control" name="title" id="edit_title">
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <input type="text" class="form-control" name="sub_title" id="edit_sub_title">
                  </div>
                </div>

                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Select or Search Countries</label><span class="validationError" id="edit_country_error"></span>
                      <select class="form-control edit_country" name="edit_country" id="edit_country">
                        @foreach($countries as $country)
                          <option value="{{$country->id}}">{{$country->country_name}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Select or Search Departures</label> <span class="validationError" id="edit_departures_error"></span>
                      <select class="form-control edit_departures" name="edit_departures[]" id="edit_departures" multiple="">
                      </select>
                    </div>
                </div>
                
                <div class="col-md-8 col-lg-8" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Slider Image <span style="color: #9a191e">(W:1920, H:1080)</span></label>
                    <div class="input-group">
                      <input type="file" name="edit_slider_image" id="edit_slider_image" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURLBanner(this);">
                      <button type="button" class="btn btn-primary">Choose Slider Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4" id="ifImageBanner" style="margin-top: 10px">
                  <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="edit_send_form">
              <span class="crop_text"><i class="fa fa-edit"></i> Update</span>
              <span class="crop_wait" style="display: none">                      
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            <span id="mesegess"></span>
          </div>
        </div>
      </div>
    </form>
  </div>
 
  <style type="text/css">
    .select2-selection__rendered 
    {
      margin-left: -10px;
    }
    .select2.select2-container {
      width: 100% !important;
    }
  </style>
  @endsection
  @section('footerSection')
  <script>
    $('#departures').select2({
      placeholder:'Select Departures',
    });
    $('#edit_departures').select2({
      placeholder:'Select Departures',
    });
    $('#country').select2({
      placeholder:'Select Country',
    });
    $('#edit_country').select2();

    $('#country').change(function(){
      var country_id = $(this).val(); 
      if(country_id){
          $.ajax({
             type:"GET",
             url:"{{url('/get-country-departure-ajax')}}?country_id="+country_id,
             success:function(res){

              if(res && res.length > 0){
                $("#departures").html('');
                  $.each(res,function(key,value){              
                      $("#departures").append("<option value='"+value.id+"'>"+value.title+"</option>");
                  });
              }else{
                 $("#departures").empty();
              }
             }
          });
      }else{
          $("#departures").empty();
      }      
    });

    $('#edit_country').change(function(){
      $('#edit_departures ').empty();
      var country_id = $(this).val(); 
      if(country_id){
          $.ajax({
             type:"GET",
             url:"{{url('/get-country-departure-ajax')}}?country_id="+country_id,
             success:function(res){

              if(res && res.length > 0){
                $("#departures").html('');
                  $.each(res,function(key,value){              
                      $("#edit_departures").append("<option value='"+value.id+"'>"+value.title+"</option>");
                  });
              }else{
                 $("#departures").empty();
              }
             }
          });
      }else{
          $("#departures").empty();
      }      
    });
  </script>
  <script>
     $('.editSlider').on('click', function() {
        $('#editModal').modal('show');
        var id = $(this).data('id');
        var title = $(this).data('title');
        var subtitle = $(this).data('subtitle');
        var country = $(this).data('country_id');
        var sliderImage = $(this).data('image');

        var departure_id = $(this).attr("data-departureId");
        var dep_id = JSON.parse(departure_id);

        var departure_name = $(this).attr("data-departureName");
        var dep_name = JSON.parse(departure_name);

        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+sliderImage;
        $("#edit_id").val(id);
        $("#edit_title").val(title);
        $("#edit_sub_title").val(subtitle);
        $("#edit_country").select2().val(country).trigger("change");
        if(sliderImage != ''){
          $("#ifImageBanner").html('<img id="image_banner_show" onclick="triggerImageBanner()" src="'+urlpath+'" class="" width="100" height="60"/>');
        }

        $('#edit_departures').val('').trigger('change');

        for (var i = 0; i < dep_id.length; i++) {
          var $select = $("#edit_departures");
          var items = {id: dep_id[i], text: dep_name[i]};
          var data = $select.val() || [];   
          $(items).each(function () {
            if(!$select.find("option[value='" + this.id + "']").length) {
              $select.append(new Option(this.text, this.id, true, true));
            }
            data.push(this.id);
          });

          $select.val(data).trigger('change');
        }
    });

    $(document).on('hide.bs.modal','#editModal', function () {
        $('#edit_departures ').empty();
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                var slideTitle = $('#slider_title').val();
                if (slideTitle == "") {
                   $("span#slider_error").html('This field is required!');
                   $("input#slider_title").focus();
                   return false;
                }
                var slideSubTitle = $('#slider_sub_title').val();
                if (slideSubTitle == "") {
                   $("span#slider_sub_error").html('This field is required!');
                   $("input#slider_sub_title").focus();
                   return false;
                }
                var country = $('#country').val();
                if (country == "") {
                   $("span#country_error").html('Please select country!');
                   $("input#country").focus();
                   return false;
                }
                var departures = $('#departures').val();
                if (departures == "") {
                   $("span#departures_error").html('Please select departure!');
                   $("input#departures").focus();
                   return false;
                }
                var file = $("#uploadFile").val();
                if (file == '') {
                   $("span#image_error").html('Please select file!!');
                   $("input#uploadFile").focus();
                   return false;
                }
                $(".crop_wait").show();
                $(".crop_text").hide();
                var formDatas = new FormData(document.getElementById('depInfocus'));
                $.ajax({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     method: 'POST',
                     url: "{{ route('departure_slider_store') }}",
                     data: formDatas,
                     contentType: false,
                     processData: false,
                     success: function (data) {
                      console.log(data);
                         $('#gif').hide();
                         $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                         window.location = "{{ route('landing_home_slider') }}";
                     },
                     errors: function () {

                     }

                });
            });



            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                var editTitle = $('#edit_title').val();
                if (editTitle == "") {
                   $("span#edit_title_error").html('This field is required!');
                   $("input#edit_title").focus();
                   return false;
                }
                $(".crop_wait").show();
                $(".crop_text").hide();
                var editFormDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     method: 'POST',
                     url: "{{ route('departure_slider_update') }}",
                     data: editFormDatas,
                     contentType: false,
                     processData: false,
                     success: function (data) {
                      console.log(data);
                         $('#gif').hide();
                         $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                         window.location = "{{ route('landing_home_slider') }}";
                     },
                     errors: function () {

                     }

                });
            });
        });
  </script>
  <script type="text/javascript">
        $(".focusPackage").click(function () {
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to Delete this Recommended departure!"))
              $.ajax(
              {
                url: '/departure-recommended-delete/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  window.location.reload();
                }
              });
        });
</script>
<script type="text/javascript">
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
             $('#uploadFile').trigger('click');
    }  
    function readURLBanner(input) {
               if (input.files && input.files[0]) {
                   var reader = new FileReader();

                   reader.onload = function (e) {
                       $('#image_banner_show')
                           .attr('src', e.target.result);
                   };

                   reader.readAsDataURL(input.files[0]);
                 }
               }


    function triggerImageBanner(){
             $('#edit_slider_image').trigger('click');
    }
</script>
  @endsection