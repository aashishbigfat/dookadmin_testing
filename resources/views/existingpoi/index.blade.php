@extends('layouts.apps')
@section('headSection')
@section('title', 'Existing Remaining POIs Index')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Existing Remaining POIs</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Existing Remaining POIs List</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a class="btn btn-primary" href="{{route('existingpoi_create')}}" style="margin-top: -30px;">Add New</a>
              <span class="btn btn-success"  style="margin-top:-30px;">Total Existing Remaining POIs <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('existingpoi_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                  <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by experience.." value="{{$keywords}}">
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('existingpoi/data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Existing Remaining POIs</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        
                          <div class="row">
                            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                              <div class="form-group">
                              <label>Select Country</label> <span class="validationError" id="country_error"></span>
                                <select class="form-control edit_country" name="edit_country" id="edit_country">
                                @foreach($country as $list)
                                  <option value="{{$list->id}}">{{$list->country_name}}</option>
                                @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                          <input type="hidden" name="edit_id" id="edit_id">
                          <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                              <label>POI Name<span class="impValidate">*</span></label>
                              <input type="text" class="form-control" name="edit_name" id="edit_name">
                            </div>
                          </div>
                          <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label>Latitude</label> <span class="validationError" id="latitide_error"></span>
                                <input type="text" class="form-control" name="edit_latitide" id="edit_latitide">
                              </div>
                          </div>
                          <div class="col-md-3 col-lg-3 col-sm-13">
                            <div class="form-group">
                              <label>Longitude</label> <span class="validationError" id="longitude_error"></span>
                              <input type="text" class="form-control" name="edit_longitude" id="edit_longitude">
                            </div>
                          </div>
                          <div class="col-md-2 col-lg-2 col-sm-6">
                            <div class="form-group">
                              <label>Rating</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                              <input type="number" class="form-control" name="edit_rating" id="edit_rating" autocomplete="off">
                            </div>
                          </div>
                          <div class="col-md-3 col-lg-3 col-sm-6">
                            <div class="form-group">
                              <label>Type</label> <span class="validationError" id="type_error"></span>
                              <input type="text" class="form-control" name="edit_type" id="edit_type" autocomplete="off">
                            </div>
                          </div>
                          <div class="col-md-7 col-lg-7 col-sm-10">
                            <div class="form-group">
                              <label>Address</label> <span class="validationError" id="address_error"></span>
                              <textarea class="form-control" name="edit_address" id="edit_address"></textarea>
                            </div>
                          </div>
                          <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                              <label>Description<span class="impValidate">*</span></label> <span class="validationError" id="description_error"></span>
                              <textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px"> </textarea>
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
                              <label class="fbm_images" for="exampleInputFile">Image<span class="impValidate">*</span> <span style="color: #9a191e">(W:464, H:260)</span></label>
                              <div class="input-group" style="margin-top:15px">
                              <input type="file" id="edit_images" name="edit_images" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                              <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="80" height="60"/>
                          </div>
                          <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
                              <label class="fbm_images" for="exampleInputFile">Banner Image<span class="impValidate">*</span> <span style="color: #9a191e">(W:1920, H:768)</span></label>
                              <div class="input-group" style="margin-top:15px">
                              <input type="file" id="edit_image_banner" name="edit_image_banner" accept="image/jpeg, image/jpg, image/png" onchange="readURLBanner(this);">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                              <img id="blahBanner" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="120" height="80"/>
                          </div>
                           
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer" style="text-align: left;">
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
  <style>
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}
  </style>
  @endsection
  @section('footerSection')

  <script>
    // When the edit button is clicked
    $('.existingPoiEdit').on('click', function() {
        $('#editModal').modal('show');
        
        var id = $(this).data('id');
        var country_id = $(this).data('countryid');
        var name = $(this).data('name');
        var address = $(this).data('address');
        var description = $(this).data('description');
        var rating = $(this).data('rating');
        var type = $(this).data('type');
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        var image = $(this).data('image');
        var banner = $(this).data('banner');
        
        // Base path for the images
        // var path = "<?php echo $s3url; ?>";
        // var urlpath = path + image;
        // var urlpathb = path + banner;
        
        // Set the form fields in the modal
        $("#edit_id").val(id);
        $("#edit_name").val(name);
        $("#edit_address").val(address);
        $("#edit_rating").val(rating);
        $("#edit_type").val(type);
        $("#edit_description").val(description);
        $("#edit_latitide").val(latitude);
        $("#edit_longitude").val(longitude);

        // Select the country in the dropdown
        $("#editModal").find("select[name='edit_country'] option[value='" + country_id + "']").attr('selected', 'selected');
        
        // Show the image previews
        $("#ifImage").html('<img id="blah" onclick="triggerImage()" src="'+ image +'" width="80" height="60"/>');
        $("#ifImageBanner").html('<img id="blahBanner" onclick="triggerImageBanner()" src="'+ banner +'" width="120" height="80"/>');
    });

    // When the delete button is clicked
    $(".existingPoiDisable").click(function () {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");

        if (confirm("Are you sure you want to delete this POI?")) {
            $.ajax({
                url: '/existingpoi/delete/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    alert("There was an error deleting the POI. Please try again.");
                }
            });
        }
    });

    // Image preview function
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Trigger the image input click event
    function triggerImage() {
        $('#edit_images').trigger('click');
    }

    // Banner image preview function
    function readURLBanner(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blahBanner').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Trigger the banner image input click event
    function triggerImageBanner() {
        $('#edit_image_banner').trigger('click');
    }
</script>

   <script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var edit_id = $('#edit_id').val();
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "existingpoi/update/"+edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      $('#messages').html("<span class='sussecmsg'>Successfully Updated!</span>");
                      location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
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
        $('#ExistingPoisData').addClass('loading');
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
        $('#ExistingPoisData').removeClass('loading');
          $("#dataIndex").empty().html(data);
         // location.hash = page;
      }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
      });
  }
</script>
@endsection