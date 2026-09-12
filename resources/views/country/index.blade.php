@extends('layouts.apps')
@section('headSection')
@section('title', 'Countries')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/country.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <h1>Countries</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Countries</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <span class="btn btn-success">Total Countries <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('countries_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($status == "no") { echo "selected"; } ?>>Status..</option>
                      <option value="1" @if($status == 1 && $status != 'no') selected="" @endif>Active</option>
                      <option value="in" @if($status == 0 && $status != 'no') selected="" @endif>In Active</option>
                  </select>
                </div>
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by country.." value="{{$keywords}}">     
                </div>
                <div class="input-group-addon">
                    <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
              {{-- @if (\Session::has('success'))
                    <div class="alert alert-success text-right" style="float: right;">
                      <p class="mb-0" style="font-size: 18px;">{{\Session::get('success')}}</p>
                    </div>
                  @endif 
              --}}
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('country/index_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            <div class="modal-dialog modal-xl" role="document" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Country View</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        <div class="row">
                          <input type="hidden" name="edit_id" id="edit_id">
                          <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group">
                              <label>Country Name</label>
                              <input type="text" class="form-control" name="edit_country" id="edit_country" disabled="">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group">
                              <label>Title</label>
                              <input type="text" class="form-control" name="edit_title" id="edit_title" disabled="">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group">
                              <label>Country Slug URL</label>
                              <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url" disabled="">
                            </div>
                          </div>
                        </div>
                          <div class="row">
                            <div class="col-md-12">
                                  <div class="panel with-nav-tabs panel-default">
                                      <div class="panel-heading">
                                              <ul class="nav nav-tabs">
                                                  <li class="active"><a href="#tab1default" data-toggle="tab">Description</a></li>
                                                  <li><a href="#tab2default" data-toggle="tab">Visa Information</a></li>
                                                  <li><a href="#tab3default" data-toggle="tab">About Description</a></li>
                                                  <li><a href="#tab4default" data-toggle="tab">Guide Description</a></li>
                                                  <li><a href="#tab5default" data-toggle="tab">Tourism Description</a></li>
                                              </ul>
                                      </div>
                                      <div class="panel-body">
                                          <div class="tab-content">
                                              <div class="tab-pane fade in active" id="tab1default"><div id="edit_description"></div></div>
                                              <div class="tab-pane fade" id="tab2default"><div id="edit_visa"></div></div>
                                              <div class="tab-pane fade" id="tab3default"><div id="edit_about"></div></div>
                                              <div class="tab-pane fade" id="tab4default"><div id="edit_guide"></div></div>
                                              <div class="tab-pane fade" id="tab5default"><div id="edit_tourism"></div></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          <div class="row">
                          <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                            <label>Featured Image</label>
                            <div id="ifImage"></div>
                          </div>
                          <div class="col-md-4 col-lg-4"style="margin-top: 10px">
                            <label>Banner Image</label>
                            <div id="ifImageBanner"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </form>
  </div>
  <style type="text/css">
    .input-group-addon {margin-left: -42px;z-index: 999;}
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
   $('.countryView').on('click', function() {
    $('#editModal').modal('show');

    var id = $(this).data('id');
    var country_name = $(this).data('name');
    var description = $(this).data('description');
    var visa = $(this).data('visa');
    var guide = $(this).data('guide');
    var about = $(this).data('about');
    var tourism = $(this).data('tourism');
    var title = $(this).data('title');
    var slug_url = $(this).data('slug');

    var image = $(this).data('image');           // full signed URL
    var banner_image = $(this).data('banner');   // full signed URL

    $("#edit_id").val(id);
    $("#edit_country").val(country_name);
    $("#edit_title").val(title);
    $("#edit_slug_url").val(slug_url);
    $('#edit_description').html(description);
    $('#edit_visa').html(visa);
    $('#edit_guide').html(guide);
    $('#edit_about').html(about);
    $('#edit_tourism').html(tourism);

    if (image) {
        $("#ifImage").html('<img id="image_show" src="' + image + '" class="" width="80" height="80"/>');
    } else {
        $("#ifImage").html('');
    }

    if (banner_image) {
        $("#ifImageBanner").html('<img id="image_banner_show" src="' + banner_image + '" class="" width="120" height="80"/>');
    } else {
        $("#ifImageBanner").html('');
    }
});

  </script>
  <script type="text/javascript">
        $(".disableCountry").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");
           var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Country?"))
              $.ajax(
              {
                url: '/countries-disable/' + id,
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
</script>
  @endsection