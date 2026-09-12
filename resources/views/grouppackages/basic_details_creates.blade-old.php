@extends('layouts.apps')
@section('headSection')
@section('title', 'Group Tours')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
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
                  <!-- <select class="form-control destinations" name="destinations[]" id="destinations" multiple="">
                    
                  </select> -->
                  <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                  <input type="text" id="destinations" class="form-control destinations" placeholder="Search destinations..">
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
                  <input type="text" class="form-control" name="dep_dook_ref_id" id="dep_dook_ref_id" placeholder="Enter departure reference id">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Nights</label> <span class="validationError" id="nights_error"></span>
                  <input type="text" class="form-control" name="nights" id="nights" oninput="this.value = (this.value.length > 8) ? this.value.slice(0,8) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1); get_no_of_days(event)">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Days</label> <span class="validationError days_error" id="days_error"></span>
                  <input type="text" class="form-control" name="days" id="days" readonly="">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Group Size</label>
                  <input type="text" class="form-control" name="group_size" id="group_size">
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
              <div class="col-md-3 col-lg-3 col-sm-12">
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
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                <label>Currency Symbol</label> <span class="validationError" id="currency_symbol_error"></span>
                  <select class="form-control select2" name="currency_symbol" id="currency_symbol">
                    @foreach($symbols as $symbol)
                      <option value="{{$symbol->currency_symbol}}">{{$symbol->currency_symbol}}({{$symbol->iso_3}})</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Price</label><span class="validationError" id="price_error"></span>
                  <input type="text" class="form-control" name="price" id="price">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <label>Price Hide/Show</label></span>
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_hide" value="0" checked="">
                      Hide
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_show" value="1">
                      Show
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="form-group">
                  <label>Package Description</label> <span class="validationError" id="description_error"></span>
                  <textarea class="form-control" name="description" id="description" style="height: 100px"> </textarea>
                </div>
              </div>
              <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Featured Image <span style="color: #9a191e" >(Width:555, Height:790)</span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Package Featured Image</label> <span class="validationError" name="image_name" id="image_error"></span> 
                  <input type="file" id="featured_image" name="image_name" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  <!-- <input type="hidden" name="image_name" id="cropurl"> -->
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12" id="featured_uploaded_image">
                  <img id="blah" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/ onclick="triggerImage()">
              </div>
            </div>
              <!-- banner image -->
              <!-- <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Package Banner Image</label> <span class="validationError" id="banner_image_error"></span> 
                  <input type="file" id="banner_image" accept="image/jpeg, image/jpg, image/png">
                  <input type="hidden" name="banner_image_name" id="banner_cropurl">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12" id="banner_uploaded_image">
                  <img src="{{asset('images/no-image.png')}}" class="bannerimages_view" width="80" height="60"/>
              </div> -->
              <!-- Banner image -->
              <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Package Banner Images <span style="color: #9a191e" >(Width:1920, Height:1080)</span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3" style="margin-top: 20px">
                <input type="hidden" name="banner_image"  id="j_son_banner">
                 <label class="fbm_images" for="exampleInputFile">Banner Images</label>
                <div class="form-group">
                  <div class="input-group">
                    <input type="file" name="poi_image_banner[]" id="uploadFileBanner" multiple accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple">
                    <button type="button" class="btn btn-primary">Choose Images</button>
                  </div>
                </div>
              </div>
<!-- 
              <div class="col-md-12">
                <div id="uploaded_image_banner"></div>
              </div> -->
              <div class="col-md-9">
                <div class="multiple-images" id="FilelistBanner">
                  <ul class="thumb-Images-Banner" id="imgListBanner"></ul>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Package Multiple Images <span style="color: #9a191e" >(Width:500, Height:500)</span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3" style="margin-top: 20px">
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

              <!-- <div class="col-md-12">
                <div id="uploaded_image"></div>
              </div> -->
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
              <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Tags</label>
                    <select class="form-control tags" name="tags[]" id="tags" multiple="multiple">
                     
                    </select>
                </div>
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
                  <textarea class="form-control" name="meta_title" id="meta_title"> </textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <!--  -->
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden; display: inline-block;">
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  
  @endsection
  @section('footerSection')

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

<!-- Banner Image -->
<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", init, false); 
        var AttachmentArrayBanner = [];
        var arrCounterBanner = 0;
        var filesCounterAlertStatusBanner = false;
        var span = document.getElementById('imgListBanner');
        span.className = ("thumb-Images-Banner");
        span.id = "imgListBanner";
        function init() {
            document.querySelector('#uploadFileBanner').addEventListener('change', handleFileSelect, false);
        }
        function handleFileSelect(e) {
            if (!e.target.files) return;
            var files = e.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                var fileReader = new FileReader();
                fileReader.onload = (function (readerEvtb) {
                    return function (e) {      
                        ApplyFileValidationRulesBanner(readerEvtb)
                        RenderThumbnailBanner(e, readerEvtb);
                        FillAttachmentArrayBanner(e, readerEvtb)
                    };
                })(f);
                fileReader.readAsDataURL(f);
            }
          multiimage =  document.getElementById('uploadFileBanner').addEventListener('change', handleFileSelect, false);
        }
        jQuery(function ($) {
            $('div').on('click', '.img-wrap-banner .close', function () {
                var id = $(this).closest('.img-wrap-banner').find('img').data('id');
                var elementPos = AttachmentArrayBanner.map(function (x) { return x.FileName; }).indexOf(id);
                if (elementPos !== -1) {
                    AttachmentArrayBanner.splice(elementPos, 1);
                }
                $(this).parent().find('img').not().remove();
                $(this).parent().find('div').not().remove();
                $(this).parent().parent().find('div').not().remove();
                var lis = document.querySelectorAll('#imgListBanner li');
                for (var i = 0; li = lis[i]; i++) {
                    if (li.innerHTML == "") {
                        li.parentNode.removeChild(li);
                    }
                }
            });
        }
        )
        function ApplyFileValidationRulesBanner(readerEvtb)
        {
            if (CheckFileType(readerEvtb.type) == false) {
                alert("The file (" + readerEvtb.name + ") You can only upload jpeg/jpg/png/gif Images");
                e.preventDefault();
                return;
            }
            if (CheckFileSize(readerEvtb.size) == false) {
                alert("The file (" + readerEvtb.name + ") The maximum file size for uploads should not exceed 1 MB");
                e.preventDefault();
                return;
            }
            if (CheckFilesCount(AttachmentArrayBanner) == false) {
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
        function CheckFilesCount(AttachmentArrayBanner) {
            var len = 0;          
            for (var i = 0; i < AttachmentArrayBanner.length; i++) {
                if (AttachmentArrayBanner[i] !== undefined) {
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
        function RenderThumbnailBanner(e, readerEvtb)
        {
            var p = document.createElement('p');
            span.appendChild(p);
            p.innerHTML = ['<div class="img-wrap-banner"> <span title="Remove" class="close">&times;</span>' +
                '<img class="thumb" style="width:180px" src="', e.target.result, '" title="', escape(readerEvtb.name), '" data-id="',
                readerEvtb.name, '"/>' + '</div>'].join('');

            var div = document.createElement('div');
            div.className = "FileNameCaptionStyle";
            p.appendChild(div);
            div.innerHTML = [readerEvtb.name].join('');
            document.getElementById('FilelistBanner').insertBefore(span, null);
           
        }
        function FillAttachmentArrayBanner(e, readerEvtb)
        {
            AttachmentArrayBanner[arrCounterBanner] =
            {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: readerEvtb.name,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: readerEvtb.type,
                Content: e.target.result.split("base64,")[1],
                FileSizeInBytes: readerEvtb.size,
            };
            arrCounterBanner = arrCounterBanner + 1;
             $('#j_son_banner').val(JSON.stringify(AttachmentArrayBanner));
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
           var myStrings = JSON.stringify(AttachmentArrayBanner);
           $('#j_son_banner').val(myStrings);
       });
</script>

  <script src="{{asset('js/customJS/basic-details.js')}}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
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
                // var days = $('#days').val();
                // if (days == "") {
                //     $("span#title_error").hide();
                //     $("span#days_error").html('This field is required!');
                //     $("input#days").focus();
                //     return false;
                // }
                // var nights = $('#nights').val();
                // if (nights == "") {
                //     $("span#days_error").hide();
                //     $("span#nights_error").html('This field is required!');
                //     $("input#nights").focus();
                //     return false;
                // }
                
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

                $('#gif').css('visibility', 'visible');
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
                        $('#gif').hide();
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
    $( document ).ready(function() {
      $('#start_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'dd-M-yy',
        minDate: 0,
      });
    });
    $('.start-calendar').click(function () {
      $("#start_date").focus();
    });

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
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  @endsection