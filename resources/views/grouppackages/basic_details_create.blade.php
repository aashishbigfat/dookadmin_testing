@extends('layouts.apps')
@section('headSection')
@section('title', 'Group Tours')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/customCSS/package.css')}}">
@endsection
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Group Tours - Basic Detail Create</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('group_packages')}}">Group Tours</a></li>
        <li class="active">Basic Detail Create</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/group_itinerary_menu')
            </div>
          </div>
        </div>
        <form role="form" id="DeparturForm">
          @csrf
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <h3>Destinations</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
              <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
                <div class="form-group">
                <span class="validationError" id="destinations_error"></span>
                  <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                  <input type="text" id="destinations" class="form-control destinations" placeholder="Search destinations.." autocomplete="off">
                  <div class="autocomplete-items" style="display: none"></div>
                  <div id="dropdest">
                   
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <h3>Basic Informations</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Title</label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title" placeholder="Enter Title">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                  <input type="text" class="form-control" name="sub_title" id="sub_title" placeholder="Enter sub title">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Package URL 1</label> <span class="validationError" id="slug_url_pre_error"></span>
                  <input type="text" class="form-control" name="slug_url_pre" id="slug_url_pre">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Package URL 2</label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Package ID</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                  <input type="text" class="form-control" name="dep_dook_ref_id" id="dep_dook_ref_id" placeholder="Enter departure reference id" autocomplete="off">
                  <span class="validationError" id="error_package_id"></span>
                </div>
              </div>
              <div class="col-md-1 col-lg-1">
                <div class="form-group">
                  <label>Nights</label> <span class="validationError" id="nights_error"></span>
                  <input type="text" class="form-control" name="nights" id="nights" oninput="this.value = (this.value.length > 8) ? this.value.slice(0,8) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1); get_no_of_days(event)">
                </div>
              </div>
              <div class="col-md-1 col-lg-1">
                <div class="form-group">
                  <label>Days</label> <span class="validationError days_error" id="days_error"></span>
                  <input type="text" class="form-control" name="days" id="days" readonly="">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Starting From</label> 
                  <input type="text" class="form-control" name="starting_from" id="starting_from">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Ending At</label> 
                  <input type="text" class="form-control" name="ending_at" id="ending_at" >
                </div>
              </div>
            </div>
              <!-- <div class="col-md-3 col-lg-3 col-sm-12" style="margin-right: 10px;">
                <div class="form-group">
                  <label>Travel Date</label> 
                  <div class="input-group date">
                    <input type="text" class="form-control pull-right" name="start_date" id="start_date" autocomplete="off">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar start-calendar"></i>
                    </div>
                  </div>

                  <span class="validationError" id="start_date_error"></span>
                </div>
              </div>
              <div class="col-md-1 col-lg-1">
                <div class="form-group">
                <label>INR</label> <span class="validationError" id="currency_symbol_error"></span>
                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control" value="{{$symbols->currency_symbol}}">
                 
                </div>
              </div>
              <div class="col-md-2 col-lg-2">
                <div class="form-group">
                  <label>Price INR</label><span class="validationError" id="price_error"></span>
                  <input type="text" class="form-control" name="price" id="price">
                </div>
              </div>-->
              <!-- Dollar Currency -->
               <!-- <div class="col-md-1 col-lg-1">
                <div class="form-group">
                <label>USD</label> <span class="validationError" id="currency_symbol_usd_error"></span>
                <input type="text" name="currency_symbol_usd" id="currency_symbol_usd" class="form-control" value="{{$symboldollar->currency_symbol}}">
                  
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Price USD</label><span class="validationError" id="price_usd_error"></span>
                  <input type="text" class="form-control" name="price_usd" id="price_usd">
                </div>
              </div> -->
            <div class="box-body">
              <div class="col-md-3 col-lg-3 col-sm-12">
                <label>Price Hide/Show</label>
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_show" value="1" checked="">
                      Show
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_hide" value="0">
                      Hide
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <label>Package Book Online</label>
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="book_online" id="booking_yes" value="1"  checked="">
                      Yes
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="book_online" id="booking_not" value="0">
                      No
                    </label>
                  </div>
                </div>
              </div>
            </div>
              <!-- <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 20px">
                <div class="form-group">
                  <label>Package Description</label> <span class="validationError" id="description_error"></span>
                  <textarea class="form-control" name="description" id="description" style="height: 100px"> </textarea>
                </div>
              </div> -->
              <div class="box-body">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Featured Image <span style="color: #9a191e" >(Width:555, Height:790)</span></label> <span class="validationError" name="image_name" id="image_error"></span> 
                  <div class="input-group">
                    <input type="file" id="featured_image" name="image_name" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                     <button type="button" class="btn btn-primary">Choose Featureed Image</button>
                  </div>
                  <!-- <input type="hidden" name="image_name" id="cropurl"> -->
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="featured_uploaded_image" style="margin-top: 15px">
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/ onclick="triggerImage()">
              </div>
              <!-- Banner -->
              <div class="col-md-4 col-lg-4">
                 <label class="fbm_images" for="exampleInputFile">Banner Image <span style="color: #9a191e" >(Width:1920, Height:760)</span></label>
                <div class="form-group">
                  <div class="input-group">
                    <input type="file" name="banner_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURLBanner(this);">
                    <button type="button" class="btn btn-primary">Choose Banner Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top: 15px">
                  <img id="blahbanner" src="{{asset('images/no-image.png')}}" class="banner_images_view" width="100" height="70" onclick="triggerImageBanner()">
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Multiple Images <span style="color: #9a191e" >(Width:500, Height:500)</span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3" style="margin-top: 10px">
                <input type="hidden" name="package_multi_img"  id="j_son">
                 <label class="fbm_images" for="exampleInputFile">Multiple Images</label>
                <div class="form-group">
                  <div class="input-group">
                    <input type="file" name="poi_image[]" id="uploadFile" multiple accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple">
                    <button type="button" class="btn btn-primary">Choose Images</button>
                    <!-- <div class="input-group-addon">.00</div> -->
                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <div class="multiple-images" id="Filelist">
                  <ul class="thumb-Images" id="imgList"></ul>
                </div>
              </div>
              <!-- / -->
            </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Tags</h3>
              <hr style="border-bottom: 2px solid #777">
             </div> 
             <div class="col-md-12 col-lg-12 col-sm-12">
             @foreach($tags as $tag)
               <div class="form-group" style="display: inline-block; margin-right: 10px;">
                  <div class="checkbox checkbox2button">
                    <label>
                      <input type="checkbox" class="checkbox_name" name="tags[]" value="{{$tag->id}}">
                        <span>{{$tag->name}} </span>
                    </label>
                  </div>
                </div>
              @endforeach
              </div>
              <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Dynamic Tags</label>
                    <select class="form-control tagsss" name="tagsss[]" id="tagss" multiple="multiple">
                      @foreach($tags as $tag)
                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div> -->
              <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Add Difficulty Tags</label>
                    <select class="form-control difficulty_tags" name="difficulty_tags[]" id="difficulty_tags" multiple="multiple">
                      @foreach($difficulty as $diffcult)
                        <option value="{{$diffcult->id}}">{{$diffcult->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Add Tour Types Tags</label>
                    <select class="form-control type_tags" name="type_tags[]" id="type_tags" multiple="multiple">
                      @foreach($tour_types as $tour_type)
                        <option value="{{$tour_type->id}}">{{$tour_type->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12 col-xl-3 sss">
                <div class="form-group edit_dest">
                  <label>Add Tour Class Tag</label>
                    <select class="form-control tour_class" name="tour_class" id="tour_class">
                      <option value="">Add Tour Class Tag</option>
                      @foreach($tour_classes as $tour_class)
                        <option value="{{$tour_class->id}}">{{$tour_class->name}}</option>
                      @endforeach
                    </select>
                </div> -->
              </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Meta Informations</h3>
              <hr style="border-bottom: 2px solid #777">
             </div>  
              
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Title</label> 
                  <textarea class="form-control" name="meta_title" id="meta_title"> </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <!--  -->
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Save</span>
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
    </section>
  </div>
  <style type="text/css">
    .btn-checkbox {}
    .btn-checkbox-checked {color: #FFF;background-color: #333;}.btn-checkbox-checked:hover {color: #FFF;background-color: #000;}.btn-group-sm>.btn, .btn-sm {font-size: 15px !important;}.form-control{padding: 6px 6px;}
  </style>
  @endsection
  @section('footerSection')
  <script>
    $(document).ready(function() {
      $('#dep_dook_ref_id').on('input', function(){
        var error_package_id = '';
        var pkg_id = $('#dep_dook_ref_id').val();
        //alert(pkg_id);
        var _token = $('input[name="_token"]').val();
        $.ajax({
          method: 'POST',
          url: "{{ route('dook_package_id__unique_check') }}",
          data: {pkg_id:pkg_id, _token:_token},

          success: function (result) {
            console.log(result);
            if(result == 'unique'){
              $('#error_package_id').html('<label class="text-success">Slug ID Availabel</label>');
              $('#dep_dook_ref_id').removeClass('has-error');
              $('#store_form').attr('disabled',false);
            }
            else{
              $('#error_package_id').html('<label class="text-danger">Slug ID already Exist!</label>');
              $('#dep_dook_ref_id').addClass('has-error');
              $('#store_form').attr('disabled','disabled');
            }
          },
        });
      });
    });
  </script>
  <!-- Multiple image -->
  <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", init, false); 
        var AttachmentArray = [];
        var arrCounter = 0;
        var filesCounterAlertStatus = false;
        var ul = document.getElementById('imgList');
        ul.className = ("thumb-Images");
        ul.id = "imgList";
        function init() {
            document.querySelector('#uploadFile').addEventListener('change', handleFileSelect, false);
        }
        function handleFileSelect(e) {
            if (!e.target.files) return;
            var files = e.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                var fileReader = new FileReader();
                fileReader.onload = (function (readerEvt) {
                    return function (e) {      
                        ApplyFileValidationRules(readerEvt)
                        RenderThumbnail(e, readerEvt);
                        FillAttachmentArray(e, readerEvt)
                    };
                })(f);
                fileReader.readAsDataURL(f);
            }
          multiimage =  document.getElementById('uploadFile').addEventListener('change', handleFileSelect, false);
        }
        jQuery(function ($) {
            $('div').on('click', '.img-wrap .close', function () {
                var id = $(this).closest('.img-wrap').find('img').data('id');
                var elementPos = AttachmentArray.map(function (x) { return x.FileName; }).indexOf(id);
                if (elementPos !== -1) {
                    AttachmentArray.splice(elementPos, 1);
                }
                $(this).parent().find('img').not().remove();
                $(this).parent().find('div').not().remove();
                $(this).parent().parent().find('div').not().remove();
                var lis = document.querySelectorAll('#imgList li');
                for (var i = 0; li = lis[i]; i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);
                    }
                }
            });
        }
        )
        function ApplyFileValidationRules(readerEvt)
        {
            if (CheckFileType(readerEvt.type) == false) {
                alert("The file (" + readerEvt.name + ") You can only upload jpeg/jpg/png/gif Images");
                e.preventDefault();
                return;
            }
            if (CheckFileSize(readerEvt.size) == false) {
                alert("The file (" + readerEvt.name + ") The maximum file size for uploads should not exceed 1 MB");
                e.preventDefault();
                return;
            }
            if (CheckFilesCount(AttachmentArray) == false) {
                if (!filesCounterAlertStatus) {
                    filesCounterAlertStatus = true;
                    alert("You have added more than 20 Images. According to upload conditions you can upload 20 Images maximum");
                }
                e.preventDefault();
                return;
            }
        }
        function CheckFileType(fileType) {
            if (fileType == "image/jpeg") {
                return true;
            }
            else if (fileType == "image/jpg") {
                return true;
            }
            else if (fileType == "image/png") {
                return true;
            }
            else if (fileType == "image/gif") {
                return true;
            }
            else {
                return false;
            }
            return true;
        }
        function CheckFileSize(fileSize) {
            if (fileSize < 1000000) {
                return true;
            }
            else {
                return false;
            }
            return true;
        }
        function CheckFilesCount(AttachmentArray) {
            var len = 0;          
            for (var i = 0; i < AttachmentArray.length; i++) {
                if (AttachmentArray[i] !== undefined) {
                    len++;
                }
            }
            if (len > 19) {
                return false;
            }
            else
            {
                return true;
            }
        }
        function RenderThumbnail(e, readerEvt)
        {
            var li = document.createElement('li');
            ul.appendChild(li);
            li.innerHTML = ['<div class="img-wrap"> <span title="Remove" class="close">&times;</span>' +
                '<img class="thumb" src="', e.target.result, '" title="', escape(readerEvt.name), '" data-id="',
                readerEvt.name, '"/>' + '</div>'].join('');

            var div = document.createElement('div');
            div.className = "FileNameCaptionStyle";
            li.appendChild(div);
            div.innerHTML = [readerEvt.name].join('');
            document.getElementById('Filelist').insertBefore(ul, null);
           
        }
        function FillAttachmentArray(e, readerEvt)
        {
            AttachmentArray[arrCounter] =
            {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvt.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvt.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvt.size,
            };
            arrCounter = arrCounter + 1;
             $('#j_son').val(JSON.stringify(AttachmentArray));
        }
         
        function toDataURL(url, callback) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
              var reader = new FileReader();
              reader.onloadend = function() {
                callback(reader.result);
              }
              reader.readAsDataURL(xhr.response);
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
          }
        $(document).on('click', '.close', function(){
           var myString = JSON.stringify(AttachmentArray);
           $('#j_son').val(myString);
       });   
</script>
  <script src="{{asset('js/customJS/basic-details.js')}}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{asset('js/checkbox2button.min.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var destinationName = $('#destinationName').val();
                if (destinationName == "") {
                    $("span#destinations_error").html('This field is required!');
                    $("input#destinations").focus();
                    return false;
                }
                var title = $('#title').val();
                if (title == "") {
                  $("span#destinations_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                var slug_url_pre = $('#slug_url_pre').val();
                if (slug_url_pre == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#title_error").hide();
                  $("span#slug_url_pre_error").html('This field is required!');
                  $("input#slug_url_pre").focus();
                  return false;
                }
                var slug_url = $('#slug_url').val();
                if (slug_url == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#slug_url_pre_error").hide();
                  $("span#slug_url_error").html('This field is required!');
                  $("input#slug_url").focus();
                  return false;
                }
                var dep_dook_ref_id = $('#dep_dook_ref_id').val();
                if (dep_dook_ref_id == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#slug_url_error").hide();
                  $("span#dep_dook_ref_id_error").html('This field is required!');
                  $("input#dep_dook_ref_id").focus();
                  return false;
                }
                var days = $('#days').val();
                if (days == "") {
                    $("span#slug_url_error").hide();
                    $("span#days_error").html('This field is required!');
                    $("input#days").focus();
                    return false;
                }
                var nights = $('#nights').val();
                if (nights == "") {
                    $("span#days_error").hide();
                    $("span#nights_error").html('This field is required!');
                    $("input#nights").focus();
                    return false;
                }
                
                // var description = $('#description').val();
                // if (description == "") {
                //     $("span#nights_error").hide();
                //     $("span#description_error").html('This field is required!');
                //     $("textarea#description").focus();
                //     return false;
                // }
                
                // var image = $('#image').val();
                // if (image == "") {
                //     $("span#description_error").hide();
                //     $("span#image_error").html('This field is required!');
                //     $("input#image").focus();
                //     return false;
                // }

                //$('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('DeparturForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('group_packages_store') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        //$('#gif').hide();
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
    // $( document ).ready(function() {
    //   $('#start_date').datepicker({
    //     changeMonth: true,
    //     changeYear: true,
    //     showButtonPanel: true,
    //     dateFormat: 'dd-M-yy',
    //     minDate: 0,
    //   });
    // });
    // $('.start-calendar').click(function () {
    //   $("#start_date").focus();
    // });

    $("li a").each(function() {   
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
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
              $('#featured_image').trigger('click');
     }
     //Banner
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
              $('#uploadFileBanner').trigger('click');
     }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  @endsection