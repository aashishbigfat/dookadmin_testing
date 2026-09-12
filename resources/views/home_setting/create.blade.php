@extends('layouts.apps')
@section('headSection')
@section('title', 'Home Settings')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Home Settings</h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Home Banner Settings</li>
    </ol>
  </section>
  <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
  <section class="content">
     
    <div class="box-body" style="margin-top: -23px;">
      <form role="form" id="homeBannerSetingPage">
      @csrf
        <div class="row">
          <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Banner Section</h3></div>
          <div class="col-md-4 sss">
           <div class="form-group edit_dest">
             <label>Banner Title</label> <span class="validationError" id="slider_error"></span><br>
             <input class="form-control" type="text" name="banner_title" id="banner_title">
           </div>
          </div>

          <div class="col-md-4 sss">
           <div class="form-group edit_dest">
             <label>Banner Subtitle</label>
             <input class="form-control" type="text" name="banner_sub_title" id="banner_sub_title">
           </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Size(1920px*768px)</label>
              <div class="input-group">
                <input type="file" name="slider_image" id="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL(this);">
                <button type="button" class="btn btn-primary">Choose Banner Image</button>
              </div>
            </div>
          </div>
          <div class="col-md-2">
             <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="130" height="60"/>
          </div>
          <div class="col-md-12" style="margin-bottom:15px;"></div>
         <div class="col-md-3 sss">
           <div class="form-group edit_dest">
             <label>Counter1</label>
             <input class="form-control" type="text" name="counter1" id="counter1">
           </div>
           <div class="form-group edit_dest">
             <label>Text</label>
             <input class="form-control" type="text" name="text1" id="text1">
           </div>
          </div>
          <div class="col-md-3 sss">
           <div class="form-group edit_dest">
             <label>Counter2</label>
             <input class="form-control" type="text" name="counter2" id="counter2">
           </div>
           <div class="form-group edit_dest">
             <label>Text</label>
             <input class="form-control" type="text" name="text2" id="text2">
           </div>
         </div>

         <div class="col-md-3 sss">
           <div class="form-group edit_dest">
             <label>Counter3</label> <span class="validationError" id="slider_sub_error"></span><br>
             <input class="form-control" type="text" name="counter3" id="counter3">
           </div>
           <div class="form-group edit_dest">
             <label>Text</label>
             <input class="form-control" type="text" name="text3" id="text3">
           </div>
          </div>
          <div class="col-md-3 sss">
           <div class="form-group edit_dest">
             <label>Counter4</label> <span class="validationError" id="slider_sub_error"></span><br>
             <input class="form-control" type="text" name="counter4" id="counter4">
           </div>
           <div class="form-group edit_dest">
             <label>Text</label>
             <input class="form-control" type="text" name="text4" id="text4">
           </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" style="margin-top: 24px;">
           <button class="btn btn-primary active mt-2" type="button" id="banner_store_form"><i class="fa fa-save"></i> Save Banner</button>
          <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
          </div>
        </div>
      </form>
      
    </div>
  </section>
</div>
 
  <style type="text/css">
    .col-md-3.sss {
      background: radial-gradient(#58545478, transparent);
      border-right: 1px solid #d1adad;
    }

    .select2-selection__rendered 
    {
      margin-left: -10px;
    }
    .select2.select2-container {
      width: 100% !important;
    }
    .uploadFile{
      opacity: 0;
      position: absolute;
      width: 100%;
      height: 100%;
    }
  </style>
  @endsection
  @section('footerSection')

  <script type="text/javascript">
    $(document).ready(function () {
      $('#banner_store_form').click(function (e) {
          e.preventDefault();
          var slideTitle = $('#banner_title').val();
          if (slideTitle == "") {
             $("span#slider_error").html('This field is required!');
             $("input#banner_title").focus();
             return false;
          }
          $('#banner_store_form').html('Please wait...')
          $('#banner_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeBannerSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_banner_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#banner_store_form').html('Save Setting')
                $('#banner_store_form').prop('disabled', false);
                $('#mesegese').html("<span class='sussecmsg'>Setting saved successfully!</span>");
                window.location.reload();
              }

          });
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
  </script>
@endsection