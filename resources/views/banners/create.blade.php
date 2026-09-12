@extends('layouts.apps')
@section('headSection')
@section('title', 'Banners')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Banner Create</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="BannerForm">
          @csrf
            <div class="box-body"  style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Banner Title<span class="impValidate">*</span></label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Banner Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                  <input type="text" class="form-control" name="sub_title" id="sub_title">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>URL<span class="impValidate">*</span></label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Days<span class="impValidate">*</span></label> <span class="validationError days_error" id="days_error"></span>
                  <input type="text" class="form-control" name="days" id="days">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Where to Show<span class="impValidate">*</span></label> <span class="validationError" id="where_show_error"></span>
                  <select class="form-control where_show" name="where_show" id="where_show">
                    <option value=""> Select Module</option>
                    <option value="depature">Depature</option>
                    <option value="experience"> Experience</option>
                    <option value="experience_single"> Experience Single</option>
                    <option value="destination"> Destination</option>
                    <option value="activity">Activity</option>
                    <option value="region">Region</option>
                    <option value="region_single">Region Single</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Banner Description<span class="impValidate">*</span></label> <span class="validationError" id="description_error"></span>
                  <textarea class="form-control" name="description" id="description" style="height: 100px"> </textarea>
                </div>
              </div>
            </div>
            <div class="box-body" style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Banner Image<span class="impValidate">*</span> <span style="color: #9a191e">(W:1250, H:520)</span></label> <span class="validationError" id="page_banner_error"></span> 
                  <div class="input-group" style="margin-top:15px">
                  <input type="file" id="banner_image" name="banner_image" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  <button type="button" class="btn btn-primary">Choose Banner Images</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top:15px">
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="120" height="80" onclick="triggerImage()">
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                    <span class="crop_wait" style="display: none">
                      Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                    </span>
                  </button>
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  <style>
    .impValidate{color:#d71921;}.validationError{color:#d71921; }
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                
                var title = $('#title').val();
                if (title == "") {
                    $(".crop_wait").hide();
                    $(".crop_text").show();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                var slug_url = $('#slug_url').val();
                if (slug_url == "") {
                    $(".crop_wait").hide();
                    $(".crop_text").show();
                    $("span#title_error").hide();
                    $("span#slug_url_error").html('This field is required!');
                    $("input#slug_url").focus();
                    return false;
                }
                var nights = $('#nights').val();
                if (nights == "") {
                    $(".crop_wait").hide();
                    $(".crop_text").show();
                    $("span#title_error").hide();
                    $("span#slug_url_error").hide();
                    $("span#nights_error").html('This field is required!');
                    $("input#nights").focus();
                    return false;
                }
                var where_show = $('#where_show').val();
                if (where_show == "") {
                    $(".crop_wait").hide();
                    $(".crop_text").show();
                    $("span#title_error").hide();
                    $("span#slug_url_error").hide();
                    $("span#nights_error").hide();
                    $("span#where_show_error").html('This field is required!');
                    $("input#where_show").focus();
                    return false;
                }
                var description = $('#description').val();
                if (description == "") {
                    $(".crop_wait").hide();
                    $(".crop_text").show();
                    $("span#title_error").hide();
                    $("span#slug_url_error").hide();
                    $("span#nights_error").hide();
                    $("span#where_show_error").hide();
                    $("span#description_error").html('This field is required!');
                    $("input#description").focus();
                    return false;
                }
                // var banner_image = $('#banner_image').val();
                // if (banner_image == "") {
                      // $(".crop_wait").hide();
                      // $(".crop_text").show();
                      // $("span#title_error").hide();
                      // $("span#slug_url_error").hide();
                //     $("span#description_error").hide();
                //     $("span#banner_image_error").html('This field is required!');
                //     $("input#banner_image").focus();
                //     return false;
                // }
                var formDatas = new FormData(document.getElementById('BannerForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('banner_store') }}",
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
  </script>
  <script>
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
      $('#banner_image').trigger('click');
    } 
  </script>
  @endsection