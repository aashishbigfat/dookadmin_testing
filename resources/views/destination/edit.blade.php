@extends('layouts.apps')
@section('headSection')
@section('title', 'Destination')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/country.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <h1>Destination Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('destination_index')}}"><i class="fa fa-dashboard"></i>Destinations</a></li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">
      <div class="row">
        <form role="form" id="destinationEditForm">
          @csrf
            <div class="box-body">
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Destination Name</label>
                  <input type="text" class="form-control" name="edit_destination" id="edit_destination" readonly ="" value="{{$destination->dest_name}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" value="{{$destination->country_name}}" readonly="">
                  </div>
                </div>
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Destination Slug URL</label>
                  <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url" value="{{$destination->slug_url}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Banner Title(1)</label>
                  <input type="text" class="form-control" name="edit_header_title" id="edit_header_title" value="{{$destination->header_title}}">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Banner Sub Title(2)</label>
                  <textarea class="form-control" name="edit_header_sub_title" id="edit_header_sub_title">{{$destination->header_sub_title}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Title(3)</label>
                  <input type="text" class="form-control" name="edit_title" id="edit_title" value="{{$destination->title}}">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Sub Title(4)</label>
                  <textarea class="form-control" name="edit_sub_title" id="edit_sub_title">{{$destination->sub_title}}</textarea>
                </div>
              </div>
              
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <label>Description(5)</label>
                  <textarea class="form-control" name="edit_description" id="edit_description" style="height: 120px">{{$destination->description}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Tours Sub Title(6)</label>
                  <textarea class="form-control" name="edit_tour_sub_title" id="edit_tour_sub_title" style="height: 80px">{{$destination->tour_sub_title}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Experience Sub Title(7)</label>
                  <textarea class="form-control" name="edit_experience_sub_title" id="edit_experience_sub_title" style="height: 80px">{{$destination->experience_sub_title}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Attraction Sub Title(8)</label>
                  <textarea class="form-control" name="edit_attraction_sub_title" id="edit_attraction_sub_title" style="height: 80px">{{$destination->attraction_sub_title}}</textarea>
                </div>
              </div>
              {{--<div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Event Sub Title(9)</label>
                  <textarea class="form-control" name="edit_event_sub_title" id="edit_event_sub_title" style="height: 80px">{{$destination->event_sub_title}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Restaurant Sub Title(10)</label>
                  <textarea class="form-control" name="edit_restaurant_sub_title" id="edit_restaurant_sub_title" style="height: 80px">{{$destination->restaurant_sub_title}}</textarea>
                </div>
              </div>--}}
              <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Plan A Trip Sub Title(11)</label>
                  <textarea class="form-control" name="edit_trip_sub_title" id="edit_trip_sub_title" style="height: 80px">{{$destination->trip_sub_title}}</textarea>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <label>Plan A Trip Description(12)</label>
                  <textarea class="form-control" name="edit_trip_description" id="edit_trip_description">{{$destination->trip_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body" style="margin-top: 10px;">
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label for="exampleInputFile" class="exampleInputFile" style="margin-bottom: 10px">Featured Image <span style="color: #9a191e;">(W:464, H:260)</span></label>
                  <div class="input-group">
                    <input type="file" class="form-control" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner edit_image" onchange="readURLImage(this);" />
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
                <div class="col-md-2 col-lg-2" id="ifImage_1">
                  <?php if($destination->image == null) { ?>
                    <img id="image_edit" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="80" height="60"/>
                  <?php } else {?>
                    <img id="image_edit" onclick="triggerImage()" src="{{generateSignedUrl('poi/'.$destination->image)}}" class="" width="80" height="60"/>
                  <?php } ?>
                </div>
                <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile" style="margin-bottom: 10px">Banner Image <span style="color: #9a191e;">(W:1920, H:768)</span> </label>
                    <div class="input-group">
                      <input type="file" name="edit_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner edit_banner_image" onchange="readURLBanner(this);">
                      <button type="button" class="btn btn-primary">Choose Images</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2" id="ifImageBanner">
                  <?php if($destination->banner_image == null) { ?>
                    <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                  <?php } else {?>
                    <img id="image_banner_show" onclick="triggerImageBanner()" src="{{generateSignedUrl('poi/'.$destination->banner_image)}}" class="" width="100" height="60"/>
                  <?php } ?>
                </div>
            </div>
            <div class="box-body" style="margin-top: 10px;">
               <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Meta Informations</h3>
                <hr style="border-bottom: 2px solid #777">
               </div> 
                
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label>Meta Title</label> 
                    <textarea class="form-control" name="meta_title" id="meta_title">{{$destination->meta_title}}</textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$destination->meta_keywords}}</textarea>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <div class="form-group">
                    <label>Meta Description</label>
                    <textarea class="form-control" name="meta_description" id="meta_description">{{$destination->meta_description}}</textarea>
                  </div>
                </div>
              </div>
            <div class="box-body" style="margin-top: 30px;">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="edit_send_form">
                  <span class="crop_text"><i class="fa fa-edit"></i> Update Destination</span>
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
<style>
  #edit_banner_image{opacity: 0;position: absolute;width: 100%;height: 100%;}#edit_image{opacity: 0;position: absolute;width: 100%;height: 100%;}
</style>
@endsection
@section('footerSection')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#edit_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        //var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('destinationEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('destination_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#messages').html("<span class='sussecmsg'>Successfully Updated!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait").hide();
            $(".crop_text").show();
            $('#messages').html("<span class='sussecmsg'>Sumthing went wrong!</span>");
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
          $('#image_edit')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#edit_image').trigger('click');
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
      $('#edit_banner_image').trigger('click');
    }
    
  </script>
  <script>
    $(document).ready(function() {
      $('#edit_trip_description').summernote({
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
      $('#edit_sub_title').summernote({
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
        height:100,
        focus: true
      });

      $('#edit_description').summernote({
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
        height:100,
        focus: true
      });

      $('#edit_tour_sub_title').summernote({
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
        height:100,
        focus: true
      });
      $('#edit_experience_sub_title').summernote({
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
        height:100,
        focus: true
      });
      $('#edit_attraction_sub_title').summernote({
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
        height:100,
        focus: true
      });
      $('#edit_trip_sub_title').summernote({
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
        height:100,
        focus: true
      });
    });
  </script>
@endsection