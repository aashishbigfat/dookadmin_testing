@extends('layouts.apps')
@section('headSection')
@section('title', 'Experiences Edit')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Experiences Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>
      <hr style="border-bottom: 2px solid #777">
    <section class="content">
      <div class="row">
        <form role="form" id="myEditForm">
          @csrf
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top: -35px;">
                <h3>Experience Informations</h3>
                <hr style="border-bottom: 2px solid #777">
              </div>
                      <div class="col-md-3 col-lg-3 col-xl-3">
                        <div class="form-group">
                          <label>Experience Name</label>
                          <input type="text" class="form-control" name="edit_name" id="edit_name" value="{{$experiences->experience_name}}" readonly="">
                        </div>
                      </div>
                      
                      <div class="col-md-3 col-lg-3 col-xl-3">
                        <div class="form-group">
                          <label>Slug URL</label><span class="validationError" id="slug_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="edit_slug" id="edit_slug" value="{{$experiences->slug_url}}">
                        </div>
                      </div>
                      <!-- div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="edit_label" id="edit_label" value="{{$experiences->label_name}}">
                        </div>
                      </div> -->
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Sub Title (8 To 12 Words)</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$experiences->sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Header Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="edit_Header_title" id="edit_Header_title" value="{{$experiences->edit_Header_title}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Header Sub Title (8 To 12 Words)</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="header_sub_title" id="header_sub_title" value="{{$experiences->header_sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><h3>Experience Section</h3>
                      <hr style="border-bottom: 2px solid #777">
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="exp_title" id="exp_title" value="{{$experiences->exp_title}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Sub Title (8 To 12 Words)</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="exp_sub_title" id="exp_sub_title" value="{{$experiences->exp_sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><h3>Package Section</h3>
                      <hr style="border-bottom: 2px solid #777">
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="pkg_title" id="pkg_title" value="{{$experiences->pkg_title}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Sub Title (8 To 12 Words)</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="pkg_sub_title" id="pkg_sub_title" value="{{$experiences->pkg_sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12"><h3>Country Section</h3>
                      <hr style="border-bottom: 2px solid #777">
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="country_title" id="country_title" value="{{$experiences->country_title}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label>Sub Title (8 To 12 Words)</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                          <input type="text" class="form-control" name="country_sub_title" id="country_sub_title" value="{{$experiences->country_sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 sss">
                        <div class="form-group">
                          <label>Experience Description</label>
                          <textarea class="form-control" name="edit_description" id="edit_description" style="height: 120px">{{$experiences->description}}</textarea>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                        <div class="form-group">
                          <label for="exampleInputFile" class="exampleInputFile">Experience Featured Image <span style="color: #9a191e">(W:666, H:703)</span></label>
                          <div class="input-group">
                            <input type="file" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURL(this);">
                            <button type="button" class="btn btn-primary">Choose Featured Images</button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                        <?php if($experiences->image == null) { ?>
                          <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="80" height="80"/>
                            <?php } else {?>
                              <img id="image_show" onclick="triggerImage()" src="{{generateSignedUrl('experience/'.$experiences->image)}}" class="" width="80" height="80"/>
                            <?php } ?>
                          </div>
                           <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
                              <label for="exampleInputFile" class="exampleInputFile">Experience Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></label>
                            <div class="input-group">
                            <input type="file" name="edit_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURLBanner(this);">
                            <button type="button" class="btn btn-primary">Choose Banner Images</button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                      <?php if($experiences->image == null) { ?>
                        <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                      <?php } else {?> 
                        <img id="image_banner_show" onclick="triggerImageBanner()" src="{{generateSignedUrl('experience/'.$experiences->banner_image)}}" class="" width="100" height="60"/>
                      <?php } ?> 
                    </div>
                </div>
              <div class="box-body">
                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                  <h3 class="inlineDisplay">{{$experiences->experience_name}} Top Activities</h3> <span class="inlineDisplay" style="color: #d71921"> Total Checked Activities: <span  id="checkboxesCount"></span></span>
                  <hr style="border-bottom: 2px solid #777">

                </div>

                @foreach($activities as $activity)
                  <div class="checkbox checked act col-md-3">
                  <label>
                  <input type="checkbox" name="activityId[]" class="activityId" value="{{$activity->id}}" @if(count($expActivities)>0) @foreach($expActivities as $act_id) 
                    @if($act_id->activity_id == $activity->id) {{'checked'}} 
                    @endif  
                    @endforeach 
                  @endif>{{$activity->activity_name}}
                  </label>
                </div>
                @endforeach
              </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Meta Informations</h3>
              <hr style="border-bottom: 2px solid #777">
             </div>  
              
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Title</label> 
                  <textarea class="form-control" name="meta_title" id="meta_title">{{$experiences->meta_title}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$experiences->meta_keywords}}</textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description">{{$experiences->meta_description}}</textarea>
                </div>
              </div>
            </div>
              <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="update_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Update</span>
                    <span class="crop_wait" style="display: none">
                      Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                    </span>
                  </button>
                  <span class="validationError" id="activity_error" style="color: #d71921"></span>
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  <style type="text/css">
    #edit_image {opacity: 0;position: absolute;width: 100%;height: 100%;}#edit_banner_image {opacity: 0;position: absolute;width: 100%;height: 100%;}.exampleInputFile{margin-bottom: 10px}.fa-spin {-webkit-animation: fa-spin 2s infinite linear;animation: fa-spin 2s infinite linear;}
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
    $(document).ready(function(){
      $('#checkboxesCount').html($("[type='checkbox']:checked").length);
      var checkboxes = $('#myEditForm input[type="checkbox"]');
      checkboxes.change(function(){
      var countCheckedCheckboxes = checkboxes.filter(':checked').length;
      if(countCheckedCheckboxes>9){
        $(this).prop('checked',false);
        alert('You can select only 9 activities');
        countCheckedCheckboxes = countCheckedCheckboxes-1;
        return false;
      }
      $('#checkboxesCount').text(countCheckedCheckboxes);
    });
  });
  </script>
  <script>
    $(document).ready(function () {
            $('#update_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                //console.log($('.activityId:checkbox:checked').length);
                // var edit_id = $('#exp_id').val();
                // if($('.activityId:checkbox:checked').length > 1 || $('.activityId:checkbox:checked').length < 1){
                //   $(".crop_wait").hide();
                //   $(".crop_text").show();
                //   $("span#activity_error").html("You Can't select more than 9 Activities!");
                //   $("#update_form").focus();
                //   return false;
                // } 
                // if($('.activityId:checkbox:checked').length < 1){
                //   $(".crop_wait").hide();
                //   $(".crop_text").show();
                //   $("span#activity_error").html("You Can't select less than 9 Activities!");
                //   $("#update_form").focus();
                //   return false;
                // } 
                var label = $('#edit_label').val();
                if (label == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#activity_error").hide();
                    $("span#label_error").html('This field is required!');
                    $("input#edit_label").focus();
                    return false;
                }
                var slug = $('#edit_slug').val();
                if (slug == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#activity_error").hide();
                  $("span#label_error").hide();
                    $("span#slug_error").html('This field is required!');
                    $("input#edit_slug").focus();
                    return false;
                }
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('experience_update',request()->route('id')) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      //console.log(data);
                        //$('#gif').hide();
                        $('#messages').html("<span class='sussecmsg'>Successfully Update!</span>");
                        //location.reload();
                        window.location = data.url;
                    },
                    errors: function () {

                    }

                });
            });
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
        }
        else{
          $("."+id).val('');
        }
      });
    });
  </script>
 <script>
    $('.destination').select2(
        {
            placeholder: 'Select Destination',
            ajax: {
                url: "/get-top-destination-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.dest_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
  </script>
   <script>
    $('#destination').change(function(){
      var dest_id = $("#destination").val();
      //console.log(dest_id)
      if(dest_id){
          $.ajax({
             type:"GET",
             url:"{{url('/get-top-dest-name')}}?dest_id="+dest_id,
             success:function(res){
              if(res && res.length > 0){
                  $.each(res,function(key,value){         
                      $("#destination_name").val(value.dest_name);  
                      $("#view_label").val(value.dest_name);
                  });
              }
             }
          });
      }  
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {

            $('#update_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var dest = $('#destination').val();
                //alert(dest);
                if (dest == null) {
                    $("span#destination_error").html('This field is required!');
                    $("select#destination").focus();
                    return false;
                }
                var label_name = $('#view_label').val();
                if (label_name == "") {
                    $("span#destination_error").html();
                    $("span#view_label_error").html('This field is required!');
                    $("input#view_label").focus();
                    return false;
                }
                // var exp = $("input[name='experiencesId']").serializeArray(); 
                //   if (exp.length === 0) 
                //   { 
                //     $("span#destination_error").html();
                //     $("span#view_label_error").html();
                //     $("span#exp_error").html('Please select atleast 1 Experience');
                //     return false;
                //   } 
                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('topDestination'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('top_destinations_update',request()->route('id')) }}",
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
    function readURL(input) {
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
              $('#edit_image').trigger('click');
     } 

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

    $(document).ready(function() {
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
        height:200,
        focus: true
      });
    });
  </script>
  
  @endsection