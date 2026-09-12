@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Home')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Home Sections Grids</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
            <div class="box-body"  style="margin-top: 15px">
              <!-- Nav tabs -->
                <ul class="nav nav-tabs TabGrid" role="tablist">
                  <li class="active"><a href="#aboutInfo" role="tab" data-toggle="tab">Section1 Grid Images</a></li>
                  <li><a href="#groupDook" role="tab" data-toggle="tab">Section2 Grid Images</a></li>
                  <!-- <li><a href="#agentSection" role="tab" data-toggle="tab">Section3 Grid Images</a></li> -->
                  <li><a href="#videoSection" role="tab" data-toggle="tab">Video Section Embed</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="responsiveTab tab-pane fade in active" id="aboutInfo">
                    <div class="one">
                    <form role="form" id="Section1Form">
                      @csrf
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Title</label> <span class="validationError" id="title_error"></span>
                          <input type="text" class="form-control" name="title" id="title">
                          <input type="hidden" class="form-control" name="section" value="{{$section1->section_no}}">
                          <input type="hidden" class="form-control" name="section_id" value="{{$section1->id}}">
                        </div>
                      </div>
                      
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Sub Title</label> <span class="validationError" id="sub_title_error"></span>
                          <input type="text" class="form-control" name="sub_title" id="sub_title" value="{{$section1->sub_title}}">
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Counter Number</label> <span class="validationError" id="counter_no_error"></span>
                          <input type="text" class="form-control" name="counter_no" id="counter_no" value="{{$section1->counter_no}}">
                          
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Sub Counter Number </label> <span class="validationError" id="title_error"></span>
                          <input type="text" class="form-control" name="sub_counter_no" id="sub_counter_no" value="{{$section1->sub_counter_no}}">
                        </div>
                      </div>
                      
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Title Redirect URL</label> <span class="validationError" id="redirect_url_error"></span>
                          <input type="text" class="form-control" name="redirect_url" id="redirect_url">
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <label>Sub Title Redirect URL</label> <span class="validationError" id="redirect_url2_error"></span>
                          <input type="text" class="form-control" name="redirect_url2" id="redirect_url2">
                        </div>
                      </div>
                      
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                         <label style="margin-bottom: 15px">Image</label> <span class="validationError" id="image_error"></span> 
                         <div class="input-group">
                            <input type="file" name="grid_image" id="uploadFile" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                          <button type="button" class="btn btn-primary">Choose Image</button>
                        </div>
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                          <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left" style="margin-top: 30px; margin-bottom: 60px;">
                          <button class="btn btn-primary active" type="button" id="section1_store_form">
                            <span class="crop_text_section1"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section1" style="display: none">
                              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                        <span class="text-success" id="mesegese1" style="margin-left: 10px"></span>
                      </div> 
                    </form>
                    </div>

                      <h4>Total Grids Section 1</h4>
                      <div class="dataIndex" id="dataIndex">
                        @include('landinghome/home_section_list1')
                      </div>
                    </div>
                    <div class="responsiveTab tab-pane fade" id="groupDook">
                      <div class="two">
                      <form role="form" id="Section2Form">
                        @csrf
                        <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Title</label> <span class="validationError" id="title1_error">
                          <input type="text" class="form-control" name="title" id="title1">
                          <input type="hidden" class="form-control" name="section" value="{{$section2->section_no}}">
                          <input type="hidden" class="form-control" name="section_id" value="{{$section2->id}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Description</label> <span class="validationError" id="description_error"></span>
                          <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                         <label style="margin-bottom: 15px">Image</label> <span class="validationError" id="image_error"></span> 
                         <div class="input-group">
                            <input type="file" name="grid_image" id="uploadFile1" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL1(this);">
                          <button type="button" class="btn btn-primary">Choose Image</button>
                        </div>
                        </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                          <img id="blah1" onclick="triggerImage1()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
                      </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left"  style="margin-top: 30px;margin-bottom: 60px;">
                          <button class="btn btn-primary active" type="button" id="section2_store_form">
                            <span class="crop_text_section2"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section2" style="display: none">
                                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                          <span class="text-success" id="mesegese2" style="margin-left: 10px"></span>
                        </div> 
                      </form>
                      </div>
                      <div class="dataIndex" id="dataIndex" style="clear: both;">
                        <h4>Total Grids Section 2 (Total = 16)</h4>
                        @include('landinghome/home_section_list2')
                      </div>
                    </div>
                   
                    <div class="responsiveTab tab-pane fade" id="videoSection">
                      <form role="form" id="Section4Form">
                        @csrf
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group">
                          <label>Video Embed Code</label> <span class="validationError" id="heading_error3"></span>
                          <textarea class="form-control" name="description" id="description2" style="height: 100px"></textarea>
                          <input type="hidden" class="form-control" name="section" value="{{$section4->section_no}}">
                          <input type="hidden" class="form-control" name="section_id" value="{{$section4->id}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left"  style="margin-top: 20px;margin-bottom: 60px">
                        <button class="btn btn-primary active" type="button" id="section4_store_form">
                          <span class="crop_text_section4"><i class="fa fa-save"></i> Update</span>
                          <span class="crop_wait_section4" style="display: none">
                              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                          </span>
                        </button>
                        <span class="text-success" id="mesegese4" style="margin-left: 10px"></span>
                      </div>
                      </form> 
                      <div class="dataIndex" id="dataIndex" style="clear: both;">
                        <h4>Total Videos</h4>
                        @include('landinghome/home_section_list4')
                      </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </section>
  </div>
  <!-- Edit Section 1 -->
  <div class="modal fade" id="editModelSection1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditFormSection1" enctype="multipart/form-data" id="myEditFormSection1">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 50%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Section1 Grid Images</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id1">
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="edit_title" id="edit_title1">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <input type="text" class="form-control" name="edit_sub_title" id="edit_sub_title1">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Counter Number</label>
                    <input type="text" class="form-control" name="edit_counter_no" id="edit_counter1">
                  </div>
                </div>
                
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Sub Counter Number</label>
                    <input type="text" class="form-control" name="edit_sub_counter_no" id="edit_sub_counter1">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title Redirect URL</label>
                    <input type="text" class="form-control" name="edit_redirect_url" id="edit_redirect_url1">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Sub Title Redirect URL</label>
                    <input type="text" class="form-control" name="edit_sub_redirect_url" id="edit_sub_redirect_url1">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Image <span style="color: #9a191e">(W:555, H:790)</span></label>
                    <div class="input-group">
                      <input type="file" name="edit_grid_image" id="edit_grid_image1" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURLSection1(this);">
                      <button type="button" class="btn btn-primary">Choose Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4" id="ifsectioniImage" style="margin-top: 10px">
                  <img id="image_section_1" onclick="triggerImageSection1()" src="{{asset('images/no-image.png')}}" class="" width="60" height="90"/>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <span id="mesegess01"></span>
            <button type="submit" class="btn btn-primary" id="updateSection1form">
              <span class="crop_text_update_section1"><i class="fa fa-edit"></i> Update</span>
              <span class="crop_wait_update_section1" style="display: none">                      
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Edit Section 2 -->
  <div class="modal fade" id="editModelSection2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditFormSection2" enctype="multipart/form-data" id="myEditFormSection2">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 45%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Section2 Grid Images</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id2">
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="edit_title" id="edit_title2">
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="edit_description" id="edit_description2" style="height: 100px"></textarea> 
                  </div>
                </div>
                <div class="col-md-8 col-lg-8" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Image <span style="color: #9a191e">(W:500, H:500)</span></label>
                    <div class="input-group" style="margin-top: 10px;">
                      <input type="file" name="edit_grid_image" id="edit_grid_image2" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURLSection2(this);">
                      <button type="button" class="btn btn-primary">Choose Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4" id="ifsectioniImage2" style="margin-top: 10px">
                  <img id="image_section_2" onclick="triggerImageSection2()" src="{{asset('images/no-image.png')}}" class="" width="100" height="100"/>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <span id="mesegess02"></span>
            <button type="submit" class="btn btn-primary" id="updateSection2form">
              <span class="crop_text_update_section2"><i class="fa fa-edit"></i> Update</span>
              <span class="crop_wait_update_section2" style="display: none">                      
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Edit Section 3 -->
  <!-- <div class="modal fade" id="editModelSection3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditFormSection3" enctype="multipart/form-data" id="myEditFormSection3">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 45%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Section3 Grid Images</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id3">
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="edit_title" id="edit_title3">
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="edit_description" id="edit_description3" style="height: 100px"></textarea> 
                  </div>
                </div>
                <div class="col-md-8 col-lg-8" style="margin-top: 5px">
                  <div class="form-group">
                    <label for="exampleInputFile" class="exampleInputFile">Image <span style="color: #9a191e">(W:500, H:500)</span></label>
                    <div class="input-group" style="margin-top: 10px;">
                      <input type="file" name="edit_grid_image" id="edit_grid_image3" accept="image/jpeg, image/jpg, image/png" class="errorMultiple" onchange="readURLSection3(this);">
                      <button type="button" class="btn btn-primary">Choose Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4" id="ifsectioniImage3" style="margin-top: 10px">
                  <img id="image_section_3" onclick="triggerImageSection3()" src="{{asset('images/no-image.png')}}" class="" width="80" height="85"/>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <span id="mesegess03"></span>
            <button type="submit" class="btn btn-primary" id="updateSection3form">
              <span class="crop_text_update_section3"><i class="fa fa-edit"></i> Update</span>
              <span class="crop_wait_update_section3" style="display: none">                      
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            
          </div>
        </div>
      </div>
    </form>
  </div> -->
  <!-- Edit Section 4 -->
  <div class="modal fade" id="editModelSection4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditFormSection4" enctype="multipart/form-data" id="myEditFormSection4">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 45%">
          <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
              <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Video Section Grid</h3></span>
              <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-close"></i></button></span>
            </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
              <div class="row">
                <input type="hidden" name="edit_id" id="edit_id4">
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="edit_description" id="edit_description4" style="height: 100px"></textarea> 
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <span id="mesegess04"></span>
            <button type="submit" class="btn btn-primary" id="updateSection4form">
              <span class="crop_text_update_section4"><i class="fa fa-edit"></i> Update</span>
              <span class="crop_wait_update_section4" style="display: none">
                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
            </button>
            
          </div>
        </div>
      </div>
    </form>
  </div>
  <style>
    .responsiveTab.tab-pane {
        margin-top: 30px;
    }
    iframe {
        width: 100px;
        height: 57px;
    }
    .TabGrid>li>a {
        font-size: 16px;
        color: #fff;
        background-color: #9a191e;
    }
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    //Section1
    $(document).ready(function () {
      $('#section1_store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_section1").show();
        $(".crop_text_section1").hide();
        var formDatas = new FormData(document.getElementById('Section1Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('landing_home_grid_images_store') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#mesegese1').html("<span class='sussecmsg'>Success!</span>");
              window.location.reload();
            },
            errors: function () {

            }

        });
      });
    });
    //Section1
    $(document).ready(function () {
      $('#section2_store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_section2").show();
        $(".crop_text_section2").hide();
        var formDatas = new FormData(document.getElementById('Section2Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('landing_home_grid_images_store') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#mesegese2').html("<span class='sussecmsg'>Success!</span>");
              window.location.reload();
            },
            errors: function () {

            }

        });
      });
    });
    //Section1
    // $(document).ready(function () {
    //   $('#section3_store_form').click(function (e) {
    //     e.preventDefault();
    //     $(".crop_wait_section3").show();
    //     $(".crop_text_section3").hide();
    //     var formDatas = new FormData(document.getElementById('Section3Form'));
    //     $.ajax({
    //       headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //       },
    //       method: 'POST',
    //       url: "{{ route('landing_home_grid_images_store') }}",
    //       data: formDatas,
    //       contentType: false,
    //       processData: false,
    //       success: function (data) {
    //         $('#mesegese3').html("<span class='sussecmsg'>Success!</span>");
    //           window.location.reload();
    //         },
    //         errors: function () {

    //         }

    //     });
    //   });
    // });
    //Section1
    $(document).ready(function () {
      $('#section4_store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_section4").show();
        $(".crop_text_section4").hide();
        var formDatas = new FormData(document.getElementById('Section4Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('landing_home_grid_images_store') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#mesegese4').html("<span class='sussecmsg'>Success!</span>");
              window.location.reload();
            },
            errors: function () {

            }

        });
      });
    });
  </script>
  <!-- Section 1 -->
  <script>
      $('.editSection1').on('click', function() {
        $('#editModelSection1').modal('show');
        var id = $(this).data('id');
        var title = $(this).data('title');
        var subtitle = $(this).data('subtitle');
        var counterno = $(this).data('counterno');
        var subcounterno = $(this).data('subcounterno');
        var redirecturl = $(this).data('redirecturl');
        var redirecturl1 = $(this).data('redirecturl2');
        //alert(redirecturl1);
        var image = $(this).data('image');
        //alert(dest_multi_image);
        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+image;
        $("#edit_id1").val(id);
        $("#edit_title1").val(title);
        $("#edit_sub_title1").val(subtitle);
        $("#edit_counter1").val(counterno);
        $('#edit_sub_counter1').val(subcounterno);
        $('#edit_redirect_url1').val(redirecturl);
        $('#edit_sub_redirect_url1').val(redirecturl1);
        if(image != ''){
          $("#ifsectioniImage").html('<img id="image_section_1" onclick="triggerImageSection1()" src="'+urlpath+'" class="" width="60" height="90"/>');
        }
      });  
      // Form Submit
      $(document).ready(function () {
        $('#updateSection1form').click(function (e) {
          e.preventDefault();
          $(".crop_wait_update_section1").show();
          $(".crop_text_update_section1").hide();
          var edit_id = $('#edit_id1').val();
          var formDatas = new FormData(document.getElementById('myEditFormSection1'));
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/landing-home-sections-grid/update/' + edit_id,
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              //console.log(data);
              $('#mesegess01').html("<span class='sussecmsg'>Successfully Update!</span>");
              location.reload();
            },
            errors: function () {

            }

          });
        });
      }); 
  </script>
  <!-- Section 2 -->
  <script>
      $('.editSection2').on('click', function() {
        $('#editModelSection2').modal('show');
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var image = $(this).data('image');
        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+image;
        $("#edit_id2").val(id);
        $("#edit_title2").val(title);
        $("#edit_description2").val(description);
        if(image != ''){
          $("#ifsectioniImage2").html('<img id="image_section_2" onclick="triggerImageSection2()" src="'+urlpath+'" class="" width="100" height="100"/>');
        }
      });  
      // Form Submit
      $(document).ready(function () {
        $('#updateSection2form').click(function (e) {
          e.preventDefault();
          $(".crop_wait_update_section2").show();
          $(".crop_text_update_section2").hide();
          var edit_id = $('#edit_id2').val();
          var formDatas = new FormData(document.getElementById('myEditFormSection2'));
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/landing-home-sections-grid/update/' + edit_id,
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              //console.log(data);
              $('#mesegess02').html("<span class='sussecmsg'>Successfully Update!</span>");
              location.reload();
            },
            errors: function () {

            }

          });
        });
      }); 
  </script>
  <!-- Section 3 -->
  <!-- <script>
      $('.editSection3').on('click', function() {
        $('#editModelSection3').modal('show');
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var image = $(this).data('image');
        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+image;
        $("#edit_id3").val(id);
        $("#edit_title3").val(title);
        $("#edit_description3").val(description);
        if(image != ''){
          $("#ifsectioniImage3").html('<img id="image_section_3" onclick="triggerImageSection3()" src="'+urlpath+'" class="" width="80" height="85"/>');
        }
      });  
      // Form Submit
      $(document).ready(function () {
        $('#updateSection3form').click(function (e) {
          e.preventDefault();
          $(".crop_wait_update_section3").show();
          $(".crop_text_update_section3").hide();
          var edit_id = $('#edit_id3').val();
          var formDatas = new FormData(document.getElementById('myEditFormSection3'));
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/landing-home-sections-grid/update/' + edit_id,
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              //console.log(data);
              $('#mesegess03').html("<span class='sussecmsg'>Successfully Update!</span>");
              location.reload();
            },
            errors: function () {

            }

          });
        });
      }); 
  </script> -->
  <!-- Section 4 -->
  <script>
      $('.editSection4').on('click', function() {
        $('#editModelSection4').modal('show');
        var id = $(this).data('id');
        var description = $(this).data('description');
        $("#edit_id4").val(id);
        $("#edit_description4").val(description);
      });  
      // Form Submit
      $(document).ready(function () {
        $('#updateSection4form').click(function (e) {
          e.preventDefault();
          $(".crop_wait_update_section4").show();
          $(".crop_text_update_section4").hide();
          var edit_id = $('#edit_id4').val();
          var formDatas = new FormData(document.getElementById('myEditFormSection4'));
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: '/landing-home-sections-grid/update/' + edit_id,
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              //console.log(data);
              $('#mesegess04').html("<span class='sussecmsg'>Successfully Update!</span>");
              location.reload();
            },
            errors: function () {

            }

          });
        });
      }); 
  </script>
  <script>
    //Section1
    function readURLSection1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_section_1')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageSection1(){
      $('#edit_grid_image1').trigger('click');
    }  
    //section2
    function readURLSection2(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_section_2')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageSection2(){
      $('#edit_grid_image2').trigger('click');
    }  
    //section3
    function readURLSection1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_section_1')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageSection1(){
      $('#edit_grid_image1').trigger('click');
    }  
  </script>
 <!--  Update Image Trigger -->
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
      $('#uploadFile').trigger('click');
    }  
    //section1
    function readURL1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#blah1')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage1(){
      $('#uploadFile1').trigger('click');
    }  
    //section2
    function readURL2(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#blah2')
            .attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage2(){
      $('#uploadFile2').trigger('click');
    }  
  </script>

  @endsection