@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Destination')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Destination Basic Details Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="PagesForm">
          @csrf
            <div class="box-body" style="margin-top: 15px">
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Title</label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title" value="{{$landings->title}}">
                  <input type="hidden" class="form-control" name="id" id="page_id" value="{{$landings->id}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                  <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$landings->sub_title}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Landing Destination Slug URL</label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url" value="{{$landings->slug_url}}">
                </div>
              </div>
            </div>
            <div class="box-body" style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Landing Page Banner Image <span style="color: #9a191e">(W:1920, H:1080)</span></label> <span class="validationError" id="page_banner_error"></span> 
                  <div class="input-group" style="margin-top:15px">
                  <input type="file" id="page_banner" name="page_banner" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top:15px">
                <?php if($landings->banner_image == null) { ?>
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
                <?php } else {?>
                  <img id="blah" src="{{generateSignedUrl('destinations/'.$landings->banner_image)}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
                <?php } ?>
              </div>
            </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Meta Informations</h3>
              <hr style="border-bottom: 2px solid #777">
             </div>  
              
            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Title</label> 
                  <textarea class="form-control" name="meta_title" id="meta_title"> {{$landings->meta_title}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$landings->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$landings->meta_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <!--  -->
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Update</span>
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
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                
                var title = $('#title').val();
                if (title == "") {
                  $("span#page_name_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                var id = $('#page_id').val();
                var formDatas = new FormData(document.getElementById('PagesForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/landing-destination/update/' + id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //location.reload();
                        window.location = data.url;
                    },
                    errors: function () {

                    }

                });
            });
        });
  </script>
  <script>
    $(document).ready(function() {
      $('#description').summernote({
          height: 150,
          focus: true
      });
    });
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
              $('#page_banner').trigger('click');
     } 
  </script>

  @endsection