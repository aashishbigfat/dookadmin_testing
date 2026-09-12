@extends('layouts.apps')
@section('headSection')
@section('title', 'Activities List')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Activities List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Activities</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <span class="btn btn-success">Total Activities <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('activity_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($status == "no") { echo "selected"; } ?>>Status..</option>
                      <option value="1" @if($status == 1 && $status != 'no') selected="" @endif>Active</option>
                      <option value="0" @if($status == 0 && $status != 'no') selected="" @endif>In Active</option>
                  </select>
                </div>
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by activities.." value="{{$keywords}}">
                    
                </div>
                <div class="input-group-addon">
                    <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('activityExperience/activity_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Activities</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        <div class="row">
                          <input type="hidden" name="edit_id" id="act_id">
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Activity Name</label>
                              <input type="text" class="form-control" name="edit_name" id="edit_name">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Activity Slug</label>
                              <input type="text" class="form-control" name="edit_slug" id="edit_slug">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Header Title</label><span class="validationError" id="label_error" style="color: #d71921"></span>
                              <input type="text" class="form-control" name="edit_header_title" id="edit_header_title">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Header Sub Title</label><span class="validationError" id="sub_title_error" style="color: #d71921"></span>
                              <input type="text" class="form-control" name="edit_header_sub_title" id="edit_header_sub_title">
                            </div>
                          </div>
                          <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 sss">
                            <div class="form-group">
                              <label>Description</label>
                              <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
                              <label for="exampleInputFile" class="exampleInputFile">Activity Featured Image <span style="color: #9a191e">(W:1024, H:768)</span></label>
                              <div class="input-group">
                                <input type="file" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURL(this);">
                                <button type="button" class="btn btn-primary">Choose Featured Images</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                              <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="80" height="80"/>
                          </div>
                           <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
                              <label for="exampleInputFile" class="exampleInputFile">Activity Banner Image <span style="color: #9a191e">(W:1920, H:760)</span></label>
                              <div class="input-group">
                                <input type="file" name="edit_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURLBanner(this);">
                                <button type="button" class="btn btn-primary">Choose Banner Images</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                              <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                          </div>
                        </div>
                        <div class="row">
                         <div class="col-md-12 col-lg-12 col-sm-12">
                          <h3>Meta Informations</h3>
                          <hr style="border-bottom: 2px solid #777">
                         </div>  
                          
                          <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <label>Meta Title</label> 
                              <textarea class="form-control" name="meta_title" id="meta_title"></textarea>
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
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="edit_send_form">
                          <span class="crop_text"><i class="fa fa-edit"></i> Update</span>
                          <span class="crop_wait" style="display: none">                      
                          <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                        </span>
                        </button>
                        <span id="mesegess"></span>
                    </div>
                </div>
            </div>
        </form>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.btn-group-sm>.btn, .btn-sm {padding: 1px 3px !important;}.inlineFlax{display: inline-flex;}#edit_banner_image {opacity: 0;position: absolute;width: 100%;height: 100%;}#edit_image {opacity: 0;position: absolute;width: 100%;height: 100%;}.exampleInputFile{margin-bottom: 10px}.fa-spin {-webkit-animation: fa-spin 2s infinite linear;animation: fa-spin 2s infinite linear;}.input-group-addon {margin-left: -42px;z-index: 999;}
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
     $('.activityEdit').on('click', function() {
    $('#editModal').modal('show');
    var id = $(this).data('id');
    var act_name = $(this).data('name');
    var slug = $(this).data('slug');
    var description = $(this).data('description');
    var header = $(this).data('header');
    var subheader = $(this).data('subheader');
    var mTitle = $(this).attr('data-mtitle');
    var mKey = $(this).data('metakey');
    var mDes = $(this).data('metadescription');
    var imageUrl = $(this).data('image');
    var bannerUrl = $(this).data('banner');

    $("#act_id").val(id);
    $("#edit_name").val(act_name);
    $("#edit_slug").val(slug);
    $("#meta_title").val(mTitle);
    $("#meta_keywords").val(mKey);
    $("#edit_header_title").val(header);
    $("#edit_header_sub_title").val(subheader);
    $("#meta_description").val(mDes);
    $('#edit_description').summernote().summernote('code', description);
    $("#ifImage").html('<img id="image_show" onclick="triggerImage()" src="'+imageUrl+'" width="80" height="80"/>');
    $("#ifImageBanner").html('<img id="image_banner_show" onclick="triggerImageBanner()" src="'+bannerUrl+'" width="100" height="60"/>');
});


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
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var edit_id = $('#act_id').val();
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/activities/update/' + edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      //console.log(data);
                        //$('#gif').hide();
                        $('#messages').html("<span class='sussecmsg'>Successfully Update!</span>");
                        location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <script type="text/javascript">
        $(".disableActivity").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");

          var flag = status?'inactive':'active';
          var token = $("meta[name='csrf-token']").attr("content");

            if (confirm("Are you sure you want to "+flag+" this Activity?"))
              $.ajax(
              {
                url: '/activities-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  window.location.reload();
                }
              });
        });
    </script>
  <script>
      //Ajax Pagination
    // $(window).on('hashchange', function() {
    //     if (window.location.hash) {
    //         var page = window.location.hash.replace('#', '');
    //         if (page == Number.NaN || page <= 0) {
    //             return false;
    //         }else{
    //             getData(page);
    //         }
    //     }
    // });
    
    // $(document).ready(function()
    // {
    //     $(document).on('click', '.pagination a',function(event)
    //     {
    //       $('#departureListData').addClass('loading');
    //         event.preventDefault();
  
    //         $('li').removeClass('active');
    //         $(this).parent('li').addClass('active');
  
    //         var myurl = $(this).attr('href');
    //         var page=$(this).attr('href').split('page=')[1];
  
    //         getData(page);
    //     });
  
    // });
  
    // function getData(page){
    //     $.ajax(
    //     {
    //         url: '?page=' + page,
    //         type: "get",
    //         datatype: "html"
    //     }).done(function(data){
    //       $('#departureListData').removeClass('loading');
    //         $("#dataIndex").empty().html(data);
    //        // location.hash = page;
    //     }).fail(function(jqXHR, ajaxOptions, thrownError){
    //           alert('No response from server');
    //     });
    // }


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