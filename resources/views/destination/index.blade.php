@extends('layouts.apps')
@section('headSection')
@section('title', 'Destinations')
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <h1>Destinations</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Destinations</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <span class="btn btn-success">Total Destinations <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('destination_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($status == "no") { echo "selected"; } ?>>Status..</option>
                      <option value="1" @if($status == 1 && $status != 'no') selected="" @endif>Active</option>
                      <option value="in" @if($status == 0 && $status != 'no') selected="" @endif>In Active</option>
                  </select>
                </div>
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by destination, country.." value="{{$keywords}}">
                    
                </div>
                <div class="input-group-addon">
                    <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('destination/index_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document" style="width: 50%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Destination</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="act_id">
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Destination</label>
                    <input type="text" class="form-control" name="view_destination" id="view_destination" readonly ="">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Slug Url</label>
                    <input type="text" class="form-control" name="view_slug" id="view_slug" readonly ="">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="form-control" name="view_country" id="view_country" readonly ="">
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <div id="view_description"></div>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4">
                  <label>Banner Image</label>
                  <div id="ifImage"></div>
                </div>
                <div class="col-md-12 col-lg-12">
                  <label>Multiple Images</label>
                  <div id="imgList"></div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
  </div>
  <!-- Edit Destination -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 60%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Destination</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Destination</label>
                    <input type="text" class="form-control" name="edit_destination" id="edit_destination">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Slug Url</label>
                    <input type="text" class="form-control" name="edit_slug" id="edit_slug">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly="">
                  </div>
                </div>
                
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="edit_description" id="edit_description" style="height: 120px;"></textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Featured Image <span style="color: #9a191e">(W:464, H:260)</span></label>
                    <div class="input-group">
                      <input type="file" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURL(this);">
                      <button type="button" class="btn btn-primary">Choose Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2" id="ifImageFe" style="margin-top: 10px">
                  <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="100" height="60"/>
                </div>
                <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></label>
                    <div class="input-group">
                      <input type="file" name="edit_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURLBanner(this);">
                      <button type="button" class="btn btn-primary">Choose Banner Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                  <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image-banner.jpg')}}" class="" width="100" height="60"/>
                </div>
                </div>
              <div class="row">
               <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Meta Informations</h3>
                <hr style="border-bottom: 2px solid #777">
               </div> 
                
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label>Meta Title</label> 
                    <textarea class="form-control" name="meta_title" id="meta_title"></textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label>Meta Keywords</label>
                    <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
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
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            <span id="mesegess"></span>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal fade" id="getMsgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" style="width: 20%;top: 30%;">
      <div class="modal-content" style="background: #eadada;">
       <div class="modal-body" id="getMsg" style="text-align: center">
       </div>
    </div>
   </div>
  </div>
 <div class="modal fade" id="getLoaderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog modal-lg" style="width: 20%;top: 30%;">
      <div class="modal-content" style="background: transparent;">
       <div class="modal-body" id="getLoader" style="text-align: center"><img src="{{asset('images/destloader.gif')}}">
       </div>
    </div>
   </div>
 </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.inlineFlax {display: inline-flex;}img.thumb.zoommodels {width: 15%;padding: 5px;}
    .input-group-addon {margin-left: -42px;z-index: 999;}#edit_banner_image{opacity: 0;position: absolute;width: 100%;height: 100%;}#edit_image{opacity: 0;position: absolute;width: 100%;height: 100%;}
  </style>
  @endsection
  @section('footerSection')
  <script>
     $('.destinationView').on('click', function() {
        $('#viewModal').modal('show');
        var id = $(this).data('id');
        var dest_name = $(this).data('name');
        var description = $(this).data('description');
        var country = $(this).data('country');
        var region = $(this).data('region');
        var image = $(this).data('image');
        var mulimages = $(this).attr("data-view-multipleimg");
        var dest_multi_image = JSON.parse(mulimages);
        //alert(dest_multi_image);
        var path = "<?php echo $s3url; ?>";
        var urlpath = path+image;
        var urlpaths = path+image;
        $("#act_id").val(id);
        $("#view_destination").val(dest_name);
        $("#view_region").val(region);
        $("#view_country").val(country);
        $('#view_description').html(description);

        $("#ifImage").html('<img id="image_show" src="'+urlpath+'" class="" style="width: 100%;" />');
        var pass = [];
            for (var i = 0; i < dest_multi_image.length; i++) {
              var url = "<?php echo $s3url; ?>";
              var paths = url + dest_multi_image[i];
              var gg = '<img class="thumb zoommodels" title="'+dest_multi_image[i]+'" src="'+ paths +'">';
              var aa = pass.push(gg);
            }
        $('#imgList').html(pass);
    });  
  </script>
  <script>
     $('.destinationEdit').on('click', function() {
        $('#editModal').modal('show');
        var id = $(this).data('id');
        var dest_name = $(this).data('name');
        var description = $(this).data('description');
        var country = $(this).data('country');
        var meta_title = $(this).data('metatital');
        var mate_key = $(this).data('metakeyword');
        var meta_des = $(this).data('metadescription');
        var bannerimage = $(this).data('bannerimage');
        var image = $(this).data('image');
        var slug_url = $(this).data('slug');
        //alert(image);
        var path = "<?php echo $s3url; ?>";
        var urlpath = path+bannerimage;
        var urlpathimg = path+image;
        $("#edit_id").val(id);
        $("#edit_destination").val(dest_name);
        $("#edit_country").val(country);
        $('#edit_description').html(description);
        $("#meta_title").val(meta_title);
        $("#meta_keywords").val(mate_key);
        $('#meta_description').html(meta_des);
        $('#edit_slug').val(slug_url);
        if(bannerimage != ''){
          $("#ifImageBanner").html('<img id="image_banner_show" onclick="triggerImageBanner()" src="'+urlpath+'" class="" width="100" height="60"/>');
        }
        if(image != ''){
          $("#ifImageFe").html('<img id="image_show" onclick="triggerImage()" src="'+urlpathimg+'" class="" width="80" height="60"/>');
        }
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#edit_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('myEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: '/destinations/update/' + edit_id,
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            //console.log(data);
            $('#mesegess').html("<span class='sussecmsg'>Successfully Update!</span>");
            location.reload();
          },
          errors: function () {

          }

        });
      });
    }); 
  </script>
  <!-- Disable destination -->
  <script type="text/javascript">
    $(".disableDestination").click(function () {
      var status = $(this).data("status");
      var flag = status?'close':'open';
      if (confirm("Are you sure you want to "+flag+" this Job?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/destinations-disable/' + id,
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    });
    </script>
  <!-- Add events -->
  <script type="text/javascript">
        $(".addEvents").click(function () {
            if (confirm("Are you sure you want to add Events for this Destination?"))
            
            var id = $(this).data("id");
            var lat = $(this).data("lat");
            var long = $(this).data("long");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
              $("#getLoaderModal").modal('show');
              $.ajax(
              {
                url: '/destination/store-events/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "lat": lat,
                    "long": long,
                    "_token": token,
                },
                success: function (data) {
                  console.log(data);
                  $("#getMsg").html(data);
                  $("#getLoaderModal").modal('hide');
                  jQuery("#getMsgModal").modal('show');
                  setTimeout(function() {$('#getMsgModal').modal('hide');}, 1500);
                  window.location.reload();
                }
              });
            }
        });
    </script>
    <!-- Add Restaurants -->
    <script type="text/javascript">
        $(".addRestaurants").click(function () {
            if (confirm("Are you sure you want to add Restaurants for this Destination?"))
            
            var id = $(this).data("id");
            var destination = $(this).data("destination");
            var lat = $(this).data("lat");
            var long = $(this).data("long");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
              $("#getLoaderModal").modal('show');
              $.ajax(
              {
                url: '/destination/store-restaurants/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "destination": destination,
                    "lat": lat,
                    "long": long,
                    "_token": token,
                },
                success: function (data) {
                  console.log(data);
                  $("#getMsg").html(data);
                  $("#getLoaderModal").modal('hide');
                  jQuery("#getMsgModal").modal('show');
                  setTimeout(function() {$('#getMsgModal').modal('hide');}, 1500);
                  window.location.reload();
                }
              });
            }
        });
    </script>
    <!-- Add Restaurants -->
    <script type="text/javascript">
        // $(".addHotels").click(function () {
        //   $("#getLoaderModal").modal('show');
        //   var id = $(this).data("id");
        //   var destination = $(this).data("destination");
        //   var lat = $(this).data("lat");
        //   var long = $(this).data("long");
        //     var token = $("meta[name='csrf-token']").attr("content");
        //     if (confirm("Are you sure you want to add Hotels for this Destination?"))
        //       $.ajax(
        //       {
        //         url: '/destination/store-hotels/' + id,
        //         type: 'POST',
        //         data: {
        //             "id": id,
        //             "destination": destination,
        //             "lat": lat,
        //             "long": long,
        //             "_token": token,
        //         },
        //         success: function (data) {
        //           console.log(data);
        //           $("#getMsg").html(data);
        //           $("#getLoaderModal").modal('hide');
        //           jQuery("#getMsgModal").modal('show');
        //           setTimeout(function() {$('#getMsgModal').modal('hide');}, 1500);
        //           window.location.reload();
        //         }
        //       });
        // });
    </script>
    <script type="text/javascript">
        $(".papularDestination").click(function () {
          if (confirm("Are you sure you want to change?"))
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
              $.ajax(
              {
                url: '/top-destination/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Changed successfully!!");
                  window.location.reload();
                }
              });
            }
        });
    </script>

  <script>
      //Ajax Pagination
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
    
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
          $('#destinationListData').addClass('loading');
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
  
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
  
            getData(page);
        });
  
    });
  
    function getData(page){
        $.ajax(
        {
            url: '?page=' + page+'&status='+'<?php echo $status;?>'+'&keyword='+'<?php echo $keywords;?>',
            type: "get",
            datatype: "html"
        }).done(function(data){
          $('#destinationListData').removeClass('loading');
            $("#dataIndex").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
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
    </script>
  @endsection