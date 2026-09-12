@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Page Edit')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Page Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('landing_pages')}}"> Pages</a></li>
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
                  <label>Page Type <sup>*</sup></label> <span class="validationError" id="pageTypes_error"></span>
                    <select class="form-control pageTypes" name="pageTypes" id="pageTypes">
                      <option value="{{$page->type}}">{{$page->page_name}}</option>
                    </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Banner Title</label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title" value="{{$page->title}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Banner Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                  <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$page->sub_title}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Landing Slug URL</label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url" value="{{$page->slug_url}}">
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="description">{{$page->description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body" style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Landing Page Banner Image <span style="color: #9a191e">(W:1920, H:760)</span></label> <span class="validationError" id="page_banner_error"></span> 
                  <div class="input-group" style="margin-top:15px">
                  <input type="file" id="page_banner" name="page_banner" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top:15px">
                <?php if($page->banner_image == null) { ?>
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
                <?php } else {?>
                  <img id="blah" src="{{generateSignedUrl('landing/'.$page->banner_image)}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
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
                  <textarea class="form-control" name="meta_title" id="meta_title"> {{$page->meta_title}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$page->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$page->meta_description}}</textarea>
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
          var formDatas = new FormData(document.getElementById('PagesForm'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{route('landing_page_update',request()->route('id'))}}",
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
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:300,
        focus: true
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
      $('#page_banner').trigger('click');
    } 
  </script>

  @endsection