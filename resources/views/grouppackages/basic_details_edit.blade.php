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
      <h1>Group Tours - Basic Details Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('group_packages')}}">Group Tours</a></li>
        <li class="active">Basic Detail Edit</li>
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
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
              <div class="form-group">
                <span class="validationError" id="destinations_error"></span>
                <input type="hidden" name="destinations" id="destinationName" class="form-control destinationName">
                <input type="text" id="destinations" class="form-control destinations DestinationSearchChecked" placeholder="Search destinations..">
                <div class="autocomplete-items" style="display: none"></div>
                <div id="dropdest" class="DestinationSearchChecked">
                 
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="checkbox all">
                <label style="font-size: 18px;color: #e01a1a;"><input type="checkbox" id="checkAll" name="destCheckUncheck" class="destCheckUncheck" value="NoUpdate">Checked if you want to Add & Update Destinations Section</label>
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
                  <input type="text" class="form-control" name="title" id="title" value="{{$departures->title}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                  <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$departures->sub_title}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Package URL 1</label> <span class="validationError" id="slug_url_pre_error"></span>
                  <input type="text" class="form-control" name="slug_url_pre" id="slug_url_pre" value="{{$departures->slug_url_pre}}">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Package URL 2</label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url" value="{{$departures->slug_url}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Package ID</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                  <input type="text" class="form-control" name="dep_dook_ref_id" id="dep_dook_ref_id" placeholder="Enter departure reference id" value="{{$departures->dep_dook_ref_id}}" autocomplete="off">
                  <span class="validationError" id="error_package_id"></span>
                </div>
              </div>
              <div class="col-md-1 col-lg-1 col-sm-12">
                <div class="form-group">
                  <label>Nights</label> <span class="validationError" id="nights_error"></span>
                  <span class="validationError" id="night_error"></span>
                  <input type="text" class="form-control" name="nights" id="nights" value="{{$departures->no_of_nights}}" oninput="this.value = (this.value.length > 8) ? this.value.slice(0,8) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1); get_no_of_days(event)">
                </div>
              </div>
              <div class="col-md-1 col-lg-1 col-sm-12">
                <div class="form-group">
                  <label>Days</label> <span class="validationError days_error" id="days_error"></span><span class="validationError" id="day_error"></span>
                  <input type="text" class="form-control" name="days" id="days" value="{{$departures->no_of_days}}" readonly="">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Starting From</label> 
                  <input type="text" class="form-control" name="starting_from" id="starting_from" value="{{$departures->from}}">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <label>Ending At</label> 
                  <input type="text" class="form-control" name="ending_at" id="ending_at" value="{{$departures->ending_at}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <label>Price Hide/Show</label>
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_show" value="1" @if($departures->price_hide_show == 1) checked="" @endif>
                      Show
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="price_hide_show" id="price_hide" value="0" @if($departures->price_hide_show == 0) checked="" @endif>
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
                      <input type="radio" name="book_online" id="booking_yes" value="1" @if($departures->book_online == 1) checked="" @endif>
                      Yes
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="book_online" id="booking_not" value="0"@if($departures->book_online == 0) checked="" @endif>
                      No
                    </label>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 20px">
                <div class="form-group">
                  <label>Description</label> <span class="validationError" id="description_error"></span>
                  <textarea class="form-control" name="description" id="description" style="height: 100px">{{$departures->description}}</textarea>
                </div>
              </div> -->
            </div>
            <div class="box-body">
             <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label class="fbm_images" for="exampleInputFile">Featured Image <span style="color: #9a191e" >(Width:555, Height:790)</span></label> <span class="validationError" id="image_error"></span> 
                  <div class="input-group">
                  <input type="file" id="featured_image" name="image_name" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
                  <button type="button" class="btn btn-primary">Choose Featureed Image</button>
                  </div>
                  <!-- <input type="hidden" name="image_name" id="cropurl"> -->
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="featured_uploaded_image" style="margin-top: 15px">
                <?php if($departures->image == null) { ?>
                  <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="60" height="60"/>
                <?php } else {?>
                  <img id="blah" onclick="triggerImage()" src="{{$urlS3.$departures->image}}" class="" width="50" height="60"/>
                <?php } ?>
              </div>
              <!-- Banner -->
              <div class="col-md-4 col-lg-4 col-xl-4">
                <input type="hidden" name="banner_image"  id="j_son_banner">
                 <label class="fbm_images" for="exampleInputFile">Banner Image <span style="color: #9a191e" >(Width:1920, Height:768)</span></label>
                <div class="form-group">
                  <div class="input-group">
                    <input type="file" name="banner_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURLBanner(this);">
                    <button type="button" class="btn btn-primary">Choose Banner Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12" id="banner_uploaded_image" style="margin-top: 15px">
                <?php if($departures->image == null) { ?>
                  <img id="blahbanner" src="{{asset('images/no-image.png')}}" class="banner_images_view" width="100" height="70" onclick="triggerImageBanner()">
                <?php } else {?>
                  <img id="blahbanner" src="{{$urlS3.$departures->banner_image}}" class="banner_images_view" width="100" height="70" onclick="triggerImageBanner()">
                <?php } ?>
              </div>
            </div>
              <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Muliple Images <span style="color: #9a191e" >(Width:720, Height:720)</span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
              <div class="col-md-12" style="margin-bottom: 20px;"><span>
                <button type="button" id="enabledInputText" class="btn btn-info">Enable Image Upload</button>
                <button type="button" id="disabledInputText" class="btn btn-success">Disable Image Upload</button>
                 </span>
               </div>
              <div class="col-md-3" style="margin-top: 20px">
                <input type="hidden" name="package_multi_img" id="j_son" disabled="">
                 <label class="fbm_images" for="exampleInputFile">Upload Multiple Images</label>
                <div class="form-group">
                  <div class="input-group">
                    <input type="file" name="poi_image[]" id="uploadFile" multiple accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple">
                    <button type="button" class="btn btn-primary" id="multiImageDisableEnable" disabled="">Choose Images</button>
                  </div>
                </div>
              </div>

              <!-- <div class="col-md-6">
                <div id="uploaded_image"></div>
              </div> -->
              <div class="col-md-9">
                <div class="multiple-images" id="Filelist">
                  <ul class="thumb-Images" id="imgList">
                    @foreach($departureimages as $depimg)
                      <li><div class="img-wrap"> <span title="Remove" class="close">×</span><img class="thumb" src="{{ $urlS3.$depimg->image }}" title="{{$depimg->image}}" data-id="{{ $depimg->image }}"></div><div class="FileNameCaptionStyle">{{$depimg->image}}</div></li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Tags</h3>
              <hr style="border-bottom: 2px solid #777">
             </div> 
             <div class="col-md-12 col-lg-12 col-xl-12">
             @foreach($tags as $tag)
               <div class="form-group" style="display: inline-block; margin-right: 10px;">
                  <div class="checkbox checkbox2button">
                    <label>
                      <input type="checkbox" class="checkbox_name" name="tags[]" value="{{$tag->id}}" @foreach($departure_tags as $departure_tag) @if($tag->id == $departure_tag->tag_id) {{'checked'}} @endif @endforeach>
                        <span>{{$tag->name}} </span>
                    </label>
                  </div>
                </div>
              @endforeach
              </div>
             <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Dynamic Tags</label>
                    <select class="form-control tagsss" name="tagsss[]" id="tagsss" multiple="multiple">
                      @foreach($tags as $tag)
                        <option value="{{$tag->id}}" @foreach($departure_tags as $departure_tag) @if($tag->id == $departure_tag->tag_id) selected @endif @endforeach>{{$tag->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div> -->
              <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Add Difficulty Tags</label>
                    <select class="form-control difficulty_tags" name="difficulty_tags[]" id="difficulty_tags" multiple="multiple">
                      @foreach($difficulties as $difficulty)
                        <option value="{{$difficulty->id}}" @foreach($departure_difficulties as $dep_difficulty) @if($difficulty->id == $dep_difficulty->difficulty_id) selected @endif @endforeach>{{$difficulty->name}}</option>
                      @endforeach
                    </select>
                </div>
              </div>

              <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                <div class="form-group edit_dest">
                  <label>Add Tour Types Tags</label>
                    <select class="form-control type_tags" name="type_tags[]" id="type_tags" multiple="multiple">
                      @foreach($tour_types as $tour_type)
                        <option value="{{$tour_type->id}}" @foreach($departure_tour_types as $dep_tour_type) @if($tour_type->id == $dep_tour_type->tour_type_id) selected @endif @endforeach>{{$tour_type->name}}</option>
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
                        <option value="{{$tour_class->id}}" @if($departures->tour_class_id == $tour_class->id) selected @endif>{{$tour_class->name}}</option>
                      @endforeach
                    </select>
                </div>
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
                  <textarea class="form-control" name="meta_title" id="meta_title">{!! $departures->meta_title !!} </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{!! $departures->meta_keywords !!}</textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{!! $departures->meta_description !!}</textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Update</span>
                    <span class="crop_wait" style="display: none"> 
                      Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                  </span>
                </button>
                <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;display: inline-block;"> -->
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  <style>
    .btn-info {background-color: #000;border-color: #000;}.btn-info:hover{background-color: #333;border-color: #333;}.img-wrap>span{display: none}
    .DestinationSearchChecked{cursor: not-allowed;filter: alpha(opacity=65);-webkit-box-shadow: none;box-shadow: none;opacity: .65;}#checkAll{width: 20px;height: 20px;margin-left: -24px;}
    .btn-checkbox {}
    .btn-checkbox-checked {color: #FFF;background-color: #333;}.btn-checkbox-checked:hover {color: #FFF;background-color: #000;}.btn-group-sm>.btn, .btn-sm {font-size: 15px !important;}
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    $(function() {
      enable_cb();
      $("#checkAll").click(enable_cb);
    });

    function enable_cb() {
      if (this.checked) {
        $("#destinations").removeClass("DestinationSearchChecked");
        $("#dropdest").removeClass("DestinationSearchChecked");
        $(".destinations").attr('disabled', false);
        document.getElementById('dropdest').style.pointerEvents = 'auto';
        $(".destCheckUncheck").val("YesUpdate");
      } else {
        $("#destinations").addClass("DestinationSearchChecked");
        $("#dropdest").addClass("DestinationSearchChecked");
        $(".destinations").attr('disabled', true);
        document.getElementById('dropdest').style.pointerEvents = 'none';
        $(".destCheckUncheck").val("NoUpdate");
      }
    }
    //document.getElementById('id').style.pointerEvents = 'none'; // To re-enable: //document.getElementById('id').style.pointerEvents = 'auto'; 
</script>
  <script>
    $( document ).ready(function() {
      $("#enabledInputText").click(function(event){
         event.preventDefault();
         $('#j_son').removeAttr('Disabled');
         $('#multiImageDisableEnable').removeAttr('Disabled');
         $('#enabledInputText').removeClass("btn-info");
         $('#enabledInputText').addClass("btn-success");
         $('#disabledInputText').addClass("btn-info");
         $('.img-wrap>span').css("display", "block");
      });
      $("#disabledInputText").click(function(event){
         event.preventDefault();
         $('#j_son').attr('disabled', 'disabled' );
         $('#multiImageDisableEnable').attr('disabled', 'disabled' );
         $('#disabledInputText').removeClass("btn-info");
         $('#disabledInputText').addClass("btn-success");
         $('#enabledInputText').addClass("btn-info");
         $('.img-wrap>span').css("display", "none");
      });
    });
  </script>
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
            console.log(files);
            for (var i = 0, f; f = files[i]; i++) {
                var fileReader = new FileReader();
                console.log(fileReader);
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
                alert("The file (" + readerEvt.name + ") You can only upload jpeg/jpg/png Images");
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
                    alert("You have added more than 10 Images. According to upload conditions you can upload 10 Images maximum");
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
            if (len > 10) {
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

        //Fill the array of attachment
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
             //console.log(JSON.stringify(AttachmentArray));
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
           console.log(myString);
       });

    function FillAttachmentArrayAll(baseImage,imgnames)
        {
            AttachmentArray[arrCounter] =
            {
                AttachmentType: 1,
                ObjectType: 1,
                FileName: imgnames,
                FileDescription: "Attachment",
                NoteText: "",
                MimeType: "png",
                Content: baseImage.split("base64,")[1],
                //FileSizeInBytes: readerEvt.size,
            };
            arrCounter = arrCounter + 1;
             $('#j_son').val(JSON.stringify(AttachmentArray));
             //console.log(JSON.stringify(AttachmentArray));
        }
        function getBase64Image(img) {
          var canvas = document.createElement("canvas");
          canvas.width = img.width;
          canvas.height = img.height;
          var ctx = canvas.getContext("2d");
          ctx.drawImage(img, 0, 0);
          var dataURL = canvas.toDataURL("image/png");
          return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
        }
</script>
  <?php
  if($imagePath){
    //print_r($imagePath);
 foreach($imagePath as $s3url){  ?>
      <script type="text/javascript">
        //var proxyUrl = 'http://cors-anywhere.herokuapp.com/';
        var s3link='<?php  echo $s3url["link"]; ?>';
        var s3name='<?php  echo $s3url["name"]; ?>';
        //alert(s3name);
        //var s3links = proxyUrl + s3link;
        var s3links = s3link;
       convertBase64img(s3links,s3name);

     function convertBase64img(urll,imggname){
        var xhr = new XMLHttpRequest()
        xhr.open("GET", urll);
        xhr.responseType = "blob";
        xhr.send();
        xhr.addEventListener("load", function() {
        var reader = new FileReader();
        reader.readAsDataURL(xhr.response); 
        reader.addEventListener("loadend", function() {             
        //console.log(reader.result,'hfjhfjhhjfhfhfffj');
        FillAttachmentArrayAll(reader.result,imggname);
    });
  });
  }  
  </script>
  <?php }} ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{asset('js/checkbox2button.min.js')}}"></script>
  <script>
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var destinationName = $('#destinationName').val();
                if (destinationName == "") {
                    $("span#destinations_error").html('This field is required!');
                    $("select#destinations").focus();
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
                

                //$('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('DeparturForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('group_packages_update',request()->route('id')) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        //$('#gif').hide();
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
  <script src="{{asset('js/customJS/basic-details_edit.js')}}"></script>
  <script type="text/javascript">
    function initDestinationAll(Lat, Long, dest_name, actual_names, country, f_classs, f_codes, geonameids, regions, region_codes, region_ids, iso2s, iso3s, dest_img, descriptionz, destId, countLat, countLong, officeName, capital, largeCity, continent, countDesc, subCountinent, countIso2, countIso3, isdCode, internetTld, currency, cointryId, currencySymbol, currencyCode, driveOn, area, areaUnit, population, sub_continent_id, countryImage, flag) {

            dest_selected.push(
                {
                    'name':dest_name,
                    'actual_name':actual_names,
                    'country':country,
                    'id':destId,
                    'lat':Lat,
                    'long':Long,
                    'fclass':f_classs,
                    'fcodes':f_codes,
                    'geonameid':geonameids,
                    'region':regions,
                    'regioncode':region_codes,
                    'regionid':region_ids,
                    'iso2':iso2s,
                    'iso3':iso3s,
                    'destimg':dest_img,
                    'description':descriptionz,
                    'country_id':cointryId,
                    'country_lat':countLat,
                    'country_long':countLong,
                    'official_name':officeName,
                    'capital':capital,
                    'largest_city':largeCity,
                    'continent':continent,
                    'sub_continent':subCountinent,
                    'count_description':countDesc,
                    'count_iso2':countIso2,
                    'count_iso3':countIso3,
                    'isd_code':isdCode,
                    'internet_tld':internetTld,
                    'currency':currency,
                    'currency_symbol':currencySymbol,
                    'currency_code':currencyCode,
                    'drive_on':driveOn,
                    'area':area,
                    'area_unit':areaUnit,
                    'population':population,
                    //'sub_continent_name':sub_continent_name,
                    'sub_continent_id':sub_continent_id,
                    'country_image':countryImage,
                    'flag':flag,
                }
                );
            $('#destinationName').val(JSON.stringify(dest_selected));
            set_dest_html();
        }
  </script>
  <?php
    if(count($destinations) > 0){
      //print_r($imagePath);
    foreach($destinations as $destination){  ?>
        <script type="text/javascript">
          var dest_name='<?php  echo $destination->name; ?>';
          var actual_names ='<?php  echo $destination->actualname; ?>';
          var country='<?php  echo $destination->country; ?>';
          var destId='<?php  echo $destination->id; ?>';
          var Lat='<?php  echo $destination->lat; ?>';
          var Long='<?php  echo $destination->long; ?>';
          var f_classs='<?php  echo $destination->f_class; ?>';
          var f_codes='<?php  echo $destination->f_codes; ?>';
          var geonameids='<?php  echo $destination->geonameid; ?>';
          var regions='<?php  echo $destination->region; ?>';
          var region_codes='<?php  echo $destination->regioncode; ?>';
          var region_ids='<?php  echo $destination->dest_region_id; ?>';
          var iso2s='<?php  echo $destination->iso2; ?>';
          var iso3s='<?php  echo $destination->iso3; ?>';
          var dest_img='<?php  echo $destination->image; ?>';
          var descriptionz='<?php  echo $destination->description; ?>';
          //alert(s3name);
          var cointryId='<?php  echo $destination->count_id; ?>';
          var officeName='<?php  echo $destination->official_name; ?>';
          var capital='<?php  echo $destination->capital; ?>';
          var largeCity='<?php  echo $destination->largest_city; ?>';
          var continent='<?php  echo $destination->continent; ?>';
          var countDesc='<?php  echo $destination->count_des; ?>';
          var subCountinent='<?php  echo $destination->sub_continent; ?>';
          var countIso2='<?php  echo $destination->iso_2; ?>';
          var countIso3='<?php  echo $destination->iso_3; ?>';
          var isdCode='<?php  echo $destination->isd_code; ?>';
          var countLat='<?php  echo $destination->count_lat; ?>';
          var countLong='<?php  echo $destination->count_long; ?>';
          var internetTld='<?php  echo $destination->internet_tld; ?>';
          var currency='<?php  echo $destination->currency; ?>';
          var currencySymbol='<?php  echo $destination->currency_symbol; ?>';
          var currencyCode='<?php  echo $destination->currency_code; ?>';
          var driveOn='<?php  echo $destination->drives_on; ?>';
          var area='<?php  echo $destination->area; ?>';
          var areaUnit='<?php  echo $destination->area_unit; ?>';
          var population='<?php  echo $destination->population; ?>';
          //var sub_continent_name='<?php  echo $destination->sub_cont_name; ?>';
          var sub_continent_id='<?php  echo $destination->regionIds; ?>';
          var countryImage='<?php  echo $destination->count_image; ?>';
          var flag='<?php  echo $destination->flag; ?>';
          initDestinationAll(Lat, Long, dest_name, actual_names, country, f_classs, f_codes, geonameids, regions, region_codes, region_ids, iso2s, iso3s, dest_img, descriptionz, destId, countLat, countLong, officeName, capital, largeCity, continent, countDesc, subCountinent, countIso2, countIso3, isdCode, internetTld, currency, cointryId, currencySymbol, currencyCode, driveOn, area, areaUnit, population, sub_continent_id, countryImage, flag)
          
  </script>
  <?php }} ?>
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
      //alert(this.href);
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
  @endsection