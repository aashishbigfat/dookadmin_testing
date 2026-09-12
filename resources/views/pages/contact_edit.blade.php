@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Contact Us Page')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Contact Us Page</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="ContactForm">
          @csrf
            <div class="box-body">
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control" name="title" id="title" value="{{$contact->title}}">
                  <input type="hidden" class="form-control" id="contactId" value="{{$contact->id}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Sub Title</label>
                  <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$contact->sub_title}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Header Title</label>
                  <input type="text" class="form-control" name="header_title" id="header_title" value="{{$contact->header_title}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Header Sub Title</label>
                  <input type="text" class="form-control" name="header_subtitle" id="header_subtitle" value="{{$contact->header_subtitle}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Phone (Landline)</label>
                  <input type="text" class="form-control" name="phone" id="phone" value="{{$contact->phone}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>WhatsApp Number</label>
                  <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="{{$contact->whatsapp}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control" name="email" id="email" value="{{$contact->email}}">
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Facebook</label>
                  <input type="text" class="form-control" name="facebook" id="facebook" value="{{$contact->facebook}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Twitter</label>
                  <input type="text" class="form-control" name="twitter" id="twitter" value="{{$contact->twitter}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Instagram</label>
                  <input type="text" class="form-control" name="instagram" id="instagram" value="{{$contact->instagram}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Youtube</label>
                  <input type="text" class="form-control" name="youtube" id="youtube" value="{{$contact->youtube}}">
                </div>
              </div>
            </div>
            <div class="box-body" style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:760)</span></label> <span class="validationError" id="page_banner_error"></span> 
                  <div class="input-group" style="margin-top:15px">
                  <input type="file" id="page_banner" name="page_banner" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top:15px">
                <?php if($contact->banner_image == null) { ?>
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
                <?php } else {?>
                  <img id="blah" src="{{generateSignedUrl('landing/'.$contact->banner_image)}}" class="fetured_images_view" width="120" height="80"/ onclick="triggerImage()">
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
                  <textarea class="form-control" name="meta_title" id="meta_title">{{$contact->meta_title}} </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$contact->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$contact->meta_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Addresses</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
              <div class="row wrappers">
                @foreach($address as $key => $value)
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Address Title {{$key+1}}</label>
                        <input type="text" class="form-control pull-right" name="addressTitle[]" id="addressTitles{{$key}}" autocomplete="off" value="{{$value->title}}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Address {{$key+1}}</label>
                        <textarea class="form-control" id="addresss{{$key}}" name="address[]" rows="5" required>{{$value->address}}</textarea>
                        <span class="text-danger" id="addresss_error"></span>
                    </div>
                </div>
                @endforeach
                <div class="col-md-12 d-flex justify-content-between text-right" style="margin-top: 25px;">
                  <div class="floating-label"><a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><i class="fas fa-plus" disable></i> Add More</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
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
  <style type="text/css">
    .row.wrappers{
      margin-right: 0px;
      margin-left: 0px;
    }
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#store_form').click(function (e) {
          e.preventDefault();
          var id = $('#contactId').val();
          $(".crop_wait").show();
          $(".crop_text").hide();
          var formDatas = new FormData(document.getElementById('ContactForm'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "/contactus-update/"+id,
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
  <script type="text/javascript">
    $(document).ready(function () {
        var k = 0;
        var maxFields = 26; //Input fields increment limitation
        var addButtons = $('.add_button'); //Add button selector
        var wrappers = $('.wrappers'); //Input field wrapper
        //var fieldHTMLs = '';
        var x = 1;
        $(addButtons).click(function () {

          var addressTitle = '"addressTitle' + x + '"';
          var addressTitle_error = '"addressTitle_error_' + x + '"';

          var address_id = 'address' + x;
          var address__error = 'percent_error_' + x;
          var fieldHTMLs = '<div class="col-md-12" id=#rowes"><div class="row"><div class="col-md-4" style=""><label>Address Title</label><div class="form-group"><input type="text" class="form-control pull-right' + addressTitle + '" name="addressTitle[]" autocomplete="off" required> <span class="text-danger"  id="' + addressTitle_error + '"></span></div></div><div class="col-md-6"><label>Address</label><div class="form-group"><textarea class="form-control address_id" id="' + address_id + '" name="address[]" required></textarea><span class="text-danger" id="' + address__error + '"></span></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button btn btn-outline-danger"><i class="fas fa-minus"></i> Remove</a></div></div></div></div>';
          if (x < maxFields) {

              x++;
              $(wrappers).append(fieldHTMLs);
          }

          $('#'+address_id).summernote({
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
            height:100
          });
          $('.note-current-fontsize').css('font-size','16px');
        });

        $(wrappers).on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(".col-md-12").last().remove();
            x--;
        });
    });
  </script>
    @foreach($address as $key => $value)
      <script type="text/javascript">
        $(document).ready(function() {
          $('#addresss{{$key}}').summernote({
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
            height:100
          });
          $('.note-current-fontsize').css('font-size','16px');
        });
      </script>
    @endforeach
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