@extends('layouts.apps')
@section('headSection')
@section('title', 'About-us')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/country.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <h1>About Us Create|Update</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>About us</a></li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777">
    <section class="content">
      <div class="row">
        <form role="form" id="aboutUsForm">
          @csrf
            <div class="box-body">
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Banner Title</label>
                  <input type="text" class="form-control" name="banner_title" id="banner_title" value="{{$about->banner_title}}">
                </div>
              </div>
              <input type="hidden" name="edit_id" id="edit_id" value="{{$about->id}}">
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Banner Sub Title</label>
                  <input type="text" class="form-control" name="banner_sub_title" id="banner_sub_title" value="{{$about->banner_sub_title}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Heading</label>
                  <input type="text" class="form-control" name="heading" id="heading" value="{{$about->heading}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Sub Heading</label>
                  <input type="text" class="form-control" name="sub_heading" id="sub_heading" value="{{$about->sub_heading}}">
                </div>
              </div>
              <div class="box-body">
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" id="description">{{$about->description}}</textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Image</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/jpeg, image/jpg, image/png," class="errorBanner image" onchange="readURLImage(this);" />
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                    <?php if($about->image == null) { ?>
                      <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="300" height="120"/>
                    <?php } else {?>
                      <img id="image_show" onclick="triggerImage()" src="{{generateSignedUrl('about/'.$about->image)}}" class="" width="300" height="180"/>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Box 1 Title</label>
                    <input type="text" class="form-control" name="box1_title" id="box1_title" value="{{$about->box1_title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Box 1 Description</label>
                    <textarea class="form-control" name="box1_description" id="box1_description">{{$about->box1_description}}</textarea>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Box 2 Title</label>
                  <input type="text" class="form-control" name="box2_title" id="box2_title" value="{{$about->box2_title}}">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Box 2 Description</label>
                  <textarea class="form-control" name="box2_description" id="box2_description">{{$about->box2_description}}</textarea>
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Box 3 Title</label>
                  <input type="text" class="form-control" name="box3_title" id="box3_title" value="{{$about->box3_title}}">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Box 3 Description</label>
                  <textarea class="form-control" name="box3_description" id="box3_description">{{$about->box3_description}}</textarea>
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Box 4 Title</label>
                  <input type="text" class="form-control" name="box4_title" id="box4_title" value="{{$about->box4_title}}">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Box 4 Description</label>
                  <textarea class="form-control" name="box4_description" id="box4_description">{{$about->box4_description}}</textarea>
                </div>
              </div>

              <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                <div class="form-group">
                  <label for="exampleInputFile" class="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:720)</span> </label>
                  <div class="input-group">
                    <input type="file" name="banner_image" id="banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner banner_image" onchange="readURLBanner(this);">
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                <?php if($about->banner_image == null) { ?>
                  <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                <?php } else {?>
                  <img id="image_banner_show" onclick="triggerImageBanner()" src="{{generateSignedUrl('about/'.$about->banner_image)}}" class="" width="100" height="60"/>
                <?php } ?>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Meta Informations</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>  
              
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Title</label> 
                  <textarea class="form-control" name="meta_title" id="meta_title">{{$about->meta_title}} </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$about->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$about->meta_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="edit_send_form">
                  <span class="crop_text"><i class="fa fa-edit"></i> Update</span>
                  <span class="crop_wait" style="display: none">                      
                    <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
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
      $('#edit_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('aboutUsForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('about_update') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#messages').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
          }

        });
      });
    });
      
  </script>
  <script>
    function readURLImage(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#image').trigger('click');
    }
    //Banner
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
      $('#banner_image').trigger('click');
    }          
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
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
    });
    $(document).ready(function() {
      $('#box1_description').summernote({
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
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#box2_description').summernote({
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
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#box3_description').summernote({
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
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#box4_description').summernote({
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
        height:250,
        focus: true
      });
    });
  </script>
@endsection