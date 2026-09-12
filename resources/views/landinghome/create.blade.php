@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Home')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Home Sections</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
            <div class="box-body"  style="margin-top: 15px">
              <!-- Nav tabs -->
                <ul class="nav nav-tabs TabGrid" role="tablist">
                  <li class="active"><a href="#aboutInfo" role="tab" data-toggle="tab">Section 1</a></li>
                  <li><a href="#groupDook" role="tab" data-toggle="tab">Group Tours Section</a></li>
                  <!-- <li><a href="#agentSection" role="tab" data-toggle="tab">Section 3</a></li> -->
                  <li><a href="#videoSection" role="tab" data-toggle="tab">Video Section</a>
                  </li>
                  <li><a href="#metaInformation" role="tab" data-toggle="tab">Meta Informations</a></li>
                </ul>
                <div class="tab-content">
                  <div class="responsiveTab tab-pane fade in active" id="aboutInfo">
                    <form role="form" id="Section1Form">
                      @csrf
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Section Heading</label> <span class="validationError" id="heading_error"></span>
                          <input type="text" class="form-control" name="heading" id="heading" value="{{$section1->heading}}">
                          <input type="hidden" class="form-control" name="section" value="1">
                          <input type="hidden" class="form-control" id="ids" value="{{$section1->id}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Section Sub Heading</label> <span class="validationError" id="sub_heading_error"></span>
                          <input type="text" class="form-control" name="sub_heading" id="sub_heading" value="{{$section1->sub_heading}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left" style="margin-top: 30px">
                          <button class="btn btn-primary active" type="button" id="section1_update_form">
                            <span class="crop_text_section1"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section1" style="display: none">
                              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                        <span class="text-success" id="mesegese1" style="margin-left: 10px"></span>
                      </div> 
                    </form>
                    </div>
                    <div class="responsiveTab tab-pane fade" id="groupDook">
                      <form role="form" id="Section2Form">
                        @csrf
                        <div class="col-md-6 col-lg-6 col-sm-12">
                          <div class="form-group">
                            <label>Section Heading</label> <span class="validationError" id="heading_error1"></span>
                            <input type="text" class="form-control" name="heading" id="heading1" value="{{$section2->heading}}">
                            <input type="hidden" class="form-control" name="section" value="2">
                            <input type="hidden" class="form-control" id="ids1" value="{{$section2->id}}">
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                          <div class="form-group">
                            <label>Section Sub Heading</label> <span class="validationError" id="sub_heading_error"></span>
                            <input type="text" class="form-control" name="sub_heading" id="sub_heading" value="{{$section2->sub_heading}}">
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                          <div class="form-group">
                            <label>Description Header</label>
                            <textarea class="form-control" name="description_header" id="description_header" style="height: 100px;">{{$section2->description_header}}</textarea>
                          </div>
                          <div class="form-group">
                            <label>Description Footer</label>
                            <textarea class="form-control" name="description_footer" id="description_footer">{{$section2->description_footer}}</textarea>
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                          <div class="form-group">
                            <label>Description Body</label>
                            <textarea class="form-control" name="section_description" id="section_description">{{$section2->description}}</textarea>
                          </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left"  style="margin-top: 30px">
                          <button class="btn btn-primary active" type="button" id="section2_update_form">
                            <span class="crop_text_section2"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section2" style="display: none">
                                Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                          <span class="text-success" id="mesegese2" style="margin-left: 10px"></span>
                        </div> 
                      </form>
                    </div>
                   
                    <div class="responsiveTab tab-pane fade" id="videoSection">
                      <form role="form" id="Section4Form">
                        @csrf
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Section Heading</label> <span class="validationError" id="heading_error3"></span>
                          <input type="text" class="form-control" name="heading" id="heading3" value="{{$section4->heading}}">
                          <input type="hidden" class="form-control" name="section" value="4">
                          <input type="hidden" class="form-control" id="ids3" value="{{$section4->id}}">
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label>Section Sub Heading</label> <span class="validationError" id="sub_heading_error"></span>
                          <input type="text" class="form-control" name="sub_heading" id="sub_heading" value="{{$section4->sub_heading}}">
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left"  style="margin-top: 30px">
                          <button class="btn btn-primary active" type="button" id="section4_update_form">
                            <span class="crop_text_section4"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section4" style="display: none">
                              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                        <span class="text-success" id="mesegese4" style="margin-left: 10px"></span>
                      </div>
                      </form> 
                    </div>
                    <div class="responsiveTab tab-pane fade" id="metaInformation">
                      <form role="form" id="Section5Form">
                        @csrf
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label>Meta Title</label> 
                          <textarea class="form-control" name="meta_title" id="meta_title">{{$section5->meta_title}} </textarea>
                          <input type="hidden" class="form-control" name="section" value="5">
                          <input type="hidden" class="form-control" id="ids4" value="{{$section5->id}}">
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label>Meta Keywords</label>
                          <textarea class="form-control" name="meta_keywords" id="meta_keywords" style="height: 120px;">{{$section5->meta_keywords}}</textarea>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label>Meta Description</label>
                          <textarea class="form-control" name="meta_description" id="meta_description" style="height: 120px;">{{$section5->meta_description}}</textarea>
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left"  style="margin-top: 30px">
                          <button class="btn btn-primary active" type="button" id="section5_update_form">
                            <span class="crop_text_section5"><i class="fa fa-save"></i> Update</span>
                            <span class="crop_wait_section5" style="display: none">
                              Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                            </span>
                          </button>
                        <span class="text-success" id="mesegese5" style="margin-left: 10px"></span>
                      </div>
                    </form>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </section>
  </div>
  <style>
    .responsiveTab.tab-pane {
        margin-top: 30px;
    }
    .TabGrid>li>a {
        /*font-size: 16px;*/
        color: #fff;
        background-color: #9a191e;
    }
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    //Section1
    //update
    $(document).ready(function () {
      $('#section1_update_form').click(function (e) {
        e.preventDefault();
        var id = $('#ids').val();
        $(".crop_wait_section1").show();
        $(".crop_text_section1").hide();
                
        var heading = $('#heading').val();
        if (heading == "") {
          $(".crop_wait_section1").hide();
          $(".crop_text_section1").show();
          $("span#heading_error").html('This field is required!');
          $("input#heading").focus();
          return false;
        }
        var formDatas = new FormData(document.getElementById('Section1Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: '/landing-home/update/' + id,
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
    //Section 2
    //update
    $(document).ready(function () {
      $('#section2_update_form').click(function (e) {
        e.preventDefault();
        var id = $('#ids1').val();
        $(".crop_wait_section2").show();
        $(".crop_text_section2").hide();
                
        var heading = $('#heading1').val();
        if (heading == "") {
          $(".crop_wait_section2").hide();
          $(".crop_text_section2").show();
          $("span#heading_error1").html('This field is required!');
          $("input#heading1").focus();
          return false;
        }
        var formDatas = new FormData(document.getElementById('Section2Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: '/landing-home/update/' + id,
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
    //section3
    //update
    // $(document).ready(function () {
    //   $('#section3_update_form').click(function (e) {
    //     e.preventDefault();
    //     var id = $('#ids2').val();
    //     $(".crop_wait_section3").show();
    //     $(".crop_text_section3").hide();
                
    //     var heading = $('#heading2').val();
    //     if (heading == "") {
    //       $(".crop_wait_section3").hide();
    //       $(".crop_text_section3").show();
    //       $("span#heading_error2").html('This field is required!');
    //       $("input#heading2").focus();
    //       return false;
    //     }
    //     var formDatas = new FormData(document.getElementById('Section3Form'));
    //     $.ajax({
    //       headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //       },
    //       method: 'POST',
    //       url: '/landing-home/update/' + id,
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
    //section 4
    //update
    $(document).ready(function () {
      $('#section4_update_form').click(function (e) {
        e.preventDefault();
        var id = $('#ids3').val();
        $(".crop_wait_section4").show();
        $(".crop_text_section4").hide();
                
        var heading = $('#heading3').val();
        if (heading == "") {
          $(".crop_wait_section4").hide();
          $(".crop_text_section4").show();
          $("span#heading_error3").html('This field is required!');
          $("input#heading3").focus();
          return false;
        }
        var formDatas = new FormData(document.getElementById('Section4Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: '/landing-home/update/' + id,
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#mesegese3').html("<span class='sussecmsg'>Success!</span>");
              window.location.reload();
            },
            errors: function () {

            }

        });
      });
    });
    //Meta information
   //update
    $(document).ready(function () {
      $('#section5_update_form').click(function (e) {
        e.preventDefault();
        var id = $('#ids4').val();
        $(".crop_wait_section5").show();
        $(".crop_text_section5").hide();
           
        var formDatas = new FormData(document.getElementById('Section5Form'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: '/landing-home/update/' + id,
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#mesegese5').html("<span class='sussecmsg'>Success!</span>");
              window.location.reload();
            },
            errors: function () {

            }

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#section_description').summernote({
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
        height:300
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
      $('#uploadFile').trigger('click');
    }  
  </script>

  @endsection