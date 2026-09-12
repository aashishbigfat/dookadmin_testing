@extends('layouts.apps')
@section('headSection')
@section('title', 'Region Edit')
<link rel="stylesheet" href="{{asset('css/customCSS/top_destination.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Region Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Region Edit</li>
      </ol>
    </section>
      <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">
      <div class="row">
        <form role="form" id="regionFormData">
          @csrf
            <div class="box-body">
              <div class="col-md-12 col-lg-12" style="margin-top: -29px;margin-bottom: 20px">
                <div style="float: left"><label>Region Section Design Demo</label>
                  <a class="dropdown-item edit viewImage" data-toggle="modal">
                    <img src="{{asset('images/region_model.png')}}" width="80" height="80">
                  </a>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                <div class="form-group">
                <label>Grid Number</label> <span class="validationError" id="grid_number_error"></span>
                  <select class="form-control grid_number" name="grid_number" id="grid_number">
                    <option value="">Select No..</option>
                    <option value="1" data-size="(W:720, H:720)" @if($region->grid_number == 1) selected @endif>1</option>
                    <option value="2" data-size="(W:720, H:310)" @if($region->grid_number == 2) selected @endif>2</option>
                    <option value="3" data-size="(W:720, H:720)" @if($region->grid_number == 3) selected @endif>3</option>
                    <option value="4" data-size="(W:640, H:302)" @if($region->grid_number == 4) selected @endif>4</option>
                    <option value="5" data-size="(W:720, H:720)" @if($region->grid_number == 5) selected @endif>5</option>
                    <option value="6" data-size="(W:720, H:720)" @if($region->grid_number == 6) selected @endif>6</option>
                    <option value="7" data-size="(W::720, H:310)" @if($region->grid_number == 7) selected @endif>7</option>
                    <option value="8" data-size="(W::720, H:310)" @if($region->grid_number == 8) selected @endif>8</option>
                    <option value="9" data-size="(W:720, H:720)" @if($region->grid_number == 9) selected @endif>9</option>
                    <option value="10" data-size="(W:720, H:720)" @if($region->grid_number == 10) selected @endif>10</option>
                    <option value="11" data-size="(W:720, H:720)" @if($region->grid_number == 11) selected @endif>11</option>
                    <option value="12" data-size="(W:720, H:720)" @if($region->grid_number == 12) selected @endif>12</option>
                    <option value="13" data-size="(W:720, H:720)" @if($region->grid_number == 13) selected @endif>13</option>
                    <option value="14" data-size="(W:720, H:720)" @if($region->grid_number == 14) selected @endif>14</option>
                    <option value="15" data-size="(W:720, H:720)" @if($region->grid_number == 15) selected @endif>15</option>
                    <option value="16" data-size="(W:720, H:720)" @if($region->grid_number == 16) selected @endif>16</option>
                    <option value="17" data-size="(W::720, H:310)" @if($region->grid_number == 17) selected @endif>17</option>
                    <option value="18" data-size="(W::720, H:310)" @if($region->grid_number == 18) selected @endif>18</option>
                    <option value="19" data-size="(W:720, H:720)" @if($region->grid_number == 19) selected @endif>19</option>
                    <option value="20" data-size="(W::720, H:720)" @if($region->grid_number == 20) selected @endif>20</option>
                    <option value="21" data-size="(W::720, H:310)" @if($region->grid_number == 21) selected @endif>21</option>
                    <option value="22" data-size="(W:720, H:720)" @if($region->grid_number == 22) selected @endif>22</option>
                    <option value="23" data-size="(W:720, H:720)" @if($region->grid_number == 23) selected @endif>23</option>
                    <option value="24" data-size="(W:720, H:360)" @if($region->grid_number == 24) selected @endif>24</option>
                    <option value="25" data-size="(W::720, H:720)" @if($region->grid_number == 25) selected @endif>25</option>
                    <option value="26" data-size="(W::720, H:720)" @if($region->grid_number == 26) selected @endif>26</option>
                    <option value="27" data-size="(W:720, H:720)" @if($region->grid_number == 27) selected @endif>27</option>
                    <option value="28" data-size="(W:720, H:720)" @if($region->grid_number == 28) selected @endif>28</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                <div class="form-group">
                <label>Region</label> <span class="validationError" id="region_name_error"></span>
                  <input class="form-control" type="text" name="region_name" id="region_name" value="{{$region->region_name}}" disabled="">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xl-3 sss">
                <div class="form-group">
                  <label>Slug URL</label> <span class="validationError" id="slug_url_error"></span><br>
                    <input class="form-control" type="text" name="slug_url" id="slug_url" value="{{$region->slug_url}}">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xl-4 sss">
                <div class="form-group">
                  <label>Title</label> <span class="validationError" id="label_name_error"></span><br>
                  @if($region->label_name != '')
                    <input class="form-control" type="text" name="label_name" id="label_name" value="{{$region->label_name}}">
                  @else
                    <input class="form-control" type="text" name="label_name" id="label_name" value="{{$region->region_name}}">
                  @endif
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xl-8 sss">
                <div class="form-group">
                  <label>Sub Title</label> <span class="validationError" id="sub_title_error"></span><br>
                    <textarea class="form-control" name="sub_title" id="sub_title">{{$region->sub_title}}</textarea>
                </div>
              </div>
              
              <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="description">{{$region->description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Country Experience Selection</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                <label>Countries</label> <span class="validationError" id="country_error"></span>
                <div class="row">
                <div class="form-group col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                @foreach($countries as $country)
                  <div class="checkbox checked act col-md-3">
                    <label>
                      <input type="checkbox" name="countryId[]" datas="{{$country->country_name}}" id="country_{{$country->id}}" class="countryId" value="{{$country->id}}{{$loop->index}}" @foreach($region_country as $countryId) @if ($country->id == $countryId->country_id) {{'checked'}} @endif  @endforeach>{{$country->country_name}}
                      <input type="hidden" name="countryName[]" class="country_{{$country->id}}" @foreach($region_country as $countryId) @if ($country->id == $countryId->country_id) value="{{$country->country_name}}" @endif  @endforeach>
                    </label>
                  </div>
                @endforeach
                <input type="hidden" id="country_validation_check">
                </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                <label>Experiences</label> <span class="validationError" id="exp_error"></span>
                <div class="row">
                <div class="form-group col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12" id="experiences">
                  @foreach($experiences as $exp)
                  <div class="checkbox checked act col-md-2">
                  <label>
                    <input type="checkbox" name="experiencesId[]" datas="{{$exp->experience_name}}" id="exp_{{$exp->id}}" class="experiencesId" value="{{$exp->id}}{{$loop->index}}" @foreach($region_experience as $expId) @if ($exp->id == $expId->experience_id) {{'checked'}} @endif  @endforeach>{{$exp->experience_name}}

                    <input type="hidden" name="experiencesName[]" class="exp_{{$exp->id}}" @foreach($region_experience as $expId) @if ($exp->id == $expId->experience_id) value="{{$exp->experience_name}}" @endif @endforeach>
                  </label>
                  </div>
                  @endforeach
                  <input type="hidden" id="validation_check">
                </div>
              </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Image
                </h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                 <label style="margin-bottom: 15px">Region Image <span id="imgSize" style="color: #d71921">
                   @if($region->image)
                    <?php
                    $val = $urlS3.'/'.$region->image;
                      // list($width, $height) = getimagesize($val);
                      // echo "(W:". $width .", H:".$height .")";
                    ?>
                  @endif
                  </span></label> <span class="validationError" id="image_error"></span> 
                 <div class="input-group">
                    <input type="file" name="region_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                  <button type="button" class="btn btn-primary">Choose Image</button>
                </div>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <?php if($region->image == null) { ?>
                  <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
                <?php } else {?>
                  <img id="blah" onclick="triggerImage()" src="{{generateSignedUrl('region/'.$region->image)}}" class="fetured_images_view" width="100" height="100"/>
                <?php } ?>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                 <label style="margin-bottom: 15px">Region Banner Image</label> <span class="validationError" id="image_banner_error"></span> 
                 <div class="input-group">
                    <input type="file" name="region_banner_image" id="uploadFile" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURLBanner(this);">
                  <button type="button" class="btn btn-primary">Choose Banner Image</button>
                </div>
                </div>
              </div>               
              <div class="col-md-3 col-lg-3 col-sm-12">
                <?php if($region->banner_image == null) { ?>
                  <img id="blahbanner" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="120" height="80"/>
                <?php } else {?>
                  <img id="blahbanner" onclick="triggerImageBanner()" src="{{generateSignedUrl('region/'.$region->banner_image)}}" class="fetured_images_view" width="120" height="80"/>
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
                  <textarea class="form-control" name="meta_title" id="meta_title">{{$region->meta_title}} </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$region->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$region->meta_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="update_form"><i class="fa fa-save"></i> Update</button>
                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  <div class="modal fade" id="popupImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="width: 54%">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close text-right imgview" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i></button>
          <div class="itinerary-setup m-t-20" style="margin-left: -15px;margin-bottom: -15px;margin-top: -15px;">
            <img src="{{asset('images/region_model.png')}}" class="img-responsive">
          </div>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">button.close.text-right.imgview {margin-left: 107%;}span#select2-top_region-container { margin-left: -10px;margin-top: -5px;}#side_image {opacity: 0;position: absolute;width: 100%;height: 100%;}
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
    $('#grid_number').select2();
    $('.viewImage').on('click', function() {
      $('#popupImage').modal('show');
    });
  </script>
  <script type="text/javascript">
    $( document ).ready(function() {
      $('.experiencesId').change(function(){
        var isChecked = $(this).is(':checked');
        var id = $(this).attr('id');
        var data_name = $(this).attr('datas');
        if(isChecked){
          $("."+id).val(data_name);
          $("#validation_check").val(id);
        }
        else{
          $("."+id).val('');
        }
      });
    });
  </script>
  <script>
    $(document).on('click', '.countryId', function(){
        var isChecked = $(this).is(':checked');
        var id = $(this).attr('id');
        var data_name = $(this).attr('datas');
        if(isChecked){
          $("."+id).val(data_name);
          $("#country_validation_check").val(id);
        }
        else{
          $("."+id).val('');
        }
      });
  </script>
  <script>
    $('#grid_number').change(function(){
      var data_size = $(this).find(':selected').attr('data-size');
      if(data_size){
        $("#imgSize").html(data_size);
      }  
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {

            $('#update_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var grid_number = $('#grid_number').val();
                if (grid_number == "") {
                  $('#gif').hide();
                    $("span#grid_number_error").html('This field is required!');
                    $("select#grid_number").focus();
                    return false;
                }
                var label_name = $('#label_name').val();
                if (label_name == "") {
                  $('#gif').hide();
                    $("span#grid_number").html();
                    $("span#label_name_error").html('This field is required!');
                    $("input#label_name").focus();
                    return false;
                }
                // var country = $('#country_validation_check').val();
                // if (country == "") {
                //   $('#gif').hide();
                //     $("span#label_name_error").html();
                //     $("span#country_error").html('Please select atleast one Country!');
                //     $(".countryId").focus();
                //     return false;
                // }

                // var exp = $('#validation_check').val();
                // if (exp == "") {
                //   $('#gif').hide();
                //     $("span#country_error").html();
                //     $("span#exp_error").html('Please select atleast one Experience!');
                //     $(".experiencesId").focus();
                //     return false;
                // }
            
                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('regionFormData'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('region_update',request()->route('id')) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      console.log(data);
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
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
        height:350
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
      $('#uploadFileBanner').trigger('click');
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
      $('#uploadFile').trigger('click');
    }  
  </script>
  
  @endsection