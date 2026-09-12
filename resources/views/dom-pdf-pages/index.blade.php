@extends('layouts.apps')
@section('headSection')
@section('title', 'PDF Create')
<meta charset="utf-8">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('css/customCSS/package.css')}}">
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700,800,900,300 | Lato:400,700,300italic| Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Redressed&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <script src="https://adm.dookinternational.com/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://adm.dookinternational.com/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Package</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('packages')}}">Package</a></li>
        <li class="active">Basic Detail Create</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @if($departure->dep_type=="main")
                @include('layouts/itinerary_menu')
              @else
                @include('layouts/land_itinerary_menu')
              @endif
            </div>
          </div>
        </div>
        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label headings">
                        <h3 class="" style="margin-top: 0px; margin-bottom: 10px;">Create Pdf
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet pdfMenu">
                    <div class="kt-portlet__body">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active tabContentPage" data-toggle="tab" href="#kt_tabs_6_1" role="tab">Banner</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tabContentPage" data-toggle="tab" href="#kt_tabs_6_13" role="tab">Contact</a>
                            </li>
                            <li class="nav-item btn btn-success" style="float:right;">
                                <a class="nav-link tabContentList" role="tab">Pdf List</a>
                            </li>
                        </ul>
                        <div class="row" id="tabContentpages" style="margin-top: 40px;">
                            <div class="col-lg-12 col-xl-12 col-md-12">
                                <div class="tab-content">
                                    @include('dom-pdf-pages/banner-page')
                                    @include('dom-pdf-pages/contact-page')
                                </div>

                            </div>
                            <div class="col-md-12" style="text-align:center;margin-top: 45px;margin-bottom: 30px;">
                              <button type="button" class="btn btn-danger" id="GenerateButton">Generate Pdf</button>
                              <p class="mesegese"></p>
                            </div>
                            <div class="col-lg-12 col-xl-12 col-md-12" style="padding:10px;">
                                @include('itinerary_v')
                            </div>
                        </div>
                        <div class="row tab-content-list displayNone" id="tabContentList">
                            @include('dom-pdf-pages/pdf_list')
                        </div>
                    </div> 
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>

<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}" type="text/javascript">
</script>
<script src="{{asset('js/pages/crud/forms/editors/tinymce.js')}}" type="text/javascript"></script>
<style type="text/css">
  .displayNone{
    display: none;
  }
    .uploadFileBannerP{
        opacity: unset !important;
    }
  .tox-tinymce {
      border-radius: 4px !important;
      height: 360px !important;
  }
  span.ck-file-dialog-button {
      display: none;
  }

  button.tox-tbtn.tox-tbtn--select.tox-tbtn--bespoke {
      display: none;
  }

  button.tox-tbtn.tox-tbtn--disabled {
      display: none;
  }

  .tox-toolbar__group:nth-of-type(2) {
      display: none;
  }

  .tox-toolbar__group:nth-of-type(5) {
      display: none;
  }

  .tox-tinymce {
      border-radius: 4px !important;
      height: 250px !important;
  }

  .tox-statusbar {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(3) {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(4) {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(6) {
      display: none !important;
  }

  .tox-toolbar {
      display: inline-flex !important;
  }

  h4.kt-portlet__head-title.completeItinerary {
      color: forestgreen;
  }

  .edit_image {
      width: 100%;
  }
    .pdfMenu .nav-tabs.nav-tabs-line .nav-link{padding:7px 10px;}
    .pdfMenu .nav-tabs.nav-tabs-line .nav-item{padding:0;margin: 0;}
    .pdfMenu .nav-tabs.nav-tabs-line .nav-item:not(:last-child) a{border-right:1px solid #ff8679;}
    .pdfMenu .nav-tabs.nav-tabs-line a.nav-link,.pdfMenu .nav-tabs.nav-tabs-line.nav.nav-tabs .nav-link{color: #ff8679;border-radius:0;}
    .pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success a.nav-link.active,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success a.nav-link:hover,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success.nav.nav-tabs .nav-link.active,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success.nav.nav-tabs .nav-link:hover{
        color: #1dc9b7;
        border-bottom: 1px solid #1dc9b7;
        border-right:1px solid #1dc9b7;
    }
      .btn-file-upload:after {
          content: attr(value);
          position: absolute;
          top: 0px;
          left: 0;
          bottom: 0;
          width: 48%;
          background: #795548;
          color: white;
          border-radius: 2px;
          text-align: center;
          font-size: 12px;
          line-height: 2;
          padding-top: 7px;
      }

      .btn-file-upload-edit:after {
          content: attr(value);
          position: absolute;
          top: 0px;
          left: 0;
          bottom: 0;
          width: 44%;
          background: #795548;
          color: white;
          border-radius: 2px;
          text-align: center;
          font-size: 12px;
          line-height: 2;
          padding-top: 7px;
      }

      div#file_img-error {
          margin-top: 20px;
          font-weight: 500;
      }

      .floating-label label {

          top: -17px;
          font-size: 14px;
          color: #7B401D;
          background: #fffcea;
          padding: 0 5px;
          font-weight: 500;
          transition: top 0.7s ease, opacity 0.7s ease;
          margin: 0;
          line-height: 1;
          font-size: 12px;
      }

      label.custom-file-label {
          top: 0px;
          padding-top: 12px;
          color: #555;
      }
    </style>

    <style type="text/css">
    .loader {
      position: relative;
      text-align: center;
      margin: 15px auto 35px auto;
      z-index: 9999;
      display: block;
      width: 80px;
      height: 80px;
      border: 10px solid rgba(0, 0, 0, .3);
      border-radius: 50%;
      border-top-color: #000;
      animation: spin 1s ease-in-out infinite;
      -webkit-animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to {
        -webkit-transform: rotate(360deg);
      }
    }

    @-webkit-keyframes spin {
      to {
        -webkit-transform: rotate(360deg);
      }
    }


    /** MODAL STYLING **/

    .modal-content {
      border-radius: 0px;
      box-shadow: 0 0 20px 8px rgba(0, 0, 0, 0.7);
    }

    .modal-backdrop.show {
      opacity: 0.75;
    }

    .loader-txt {
      p {
        font-size: 13px;
        color: #666;
        small {
          font-size: 11.5px;
          color: #999;
        }
      }
    }

    #output {
      padding: 25px 15px;
      background: #222;
      border: 1px solid #222;
      max-width: 350px;
      margin: 35px auto;
      font-family: 'Roboto', sans-serif !important;
      p.subtle {
        color: #555;
        font-style: italic;
        font-family: 'Roboto', sans-serif !important;
      }
      h4 {
        font-weight: 300 !important;
        font-size: 1.1em;
        font-family: 'Roboto', sans-serif !important;
      }
      p {
        font-family: 'Roboto', sans-serif !important;
        font-size: 0.9em;
        b {
          text-transform: uppercase;
          text-decoration: underline;
        }
      }
    }
</style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    $(".tabContentList").click(function(){
      $("#tabContentpages").addClass('displayNone');
      $("#tabContentList").removeClass("displayNone");
    });
    $(".tabContentPage").click(function(){
      $("#tabContentpages").removeClass('displayNone');
      $("#tabContentList").addClass("displayNone");
    });

    $("li a").each(function() {   
      //alert(this.href);
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#GenerateButton').click(function (e) {
          e.preventDefault();
          $('#GenerateButton').html('Please wait...')
          $('#GenerateButton').prop('disabled', true);
          var vv = "<?php echo request()->route('id') ?>";
          $.ajax({
              method: 'get',
              url: "{{ route('pdf_dom',request()->route('id')) }}",
              contentType: false,
              processData: false,
              success: function (data) {
                $('#GenerateButton').html('Generate Pdf')
                $('#GenerateButton').prop('disabled', false);
                  $('#mesegese').html("<span class='sussecmsgWrong'>Pdf Generated successfully!</span>");
                  window.location.reload();
              },
              errors: function () {

              }

          });
      });
    });
  </script>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#banner')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        function triggerImage(){
            $('#uploadFileBanner').trigger('click');
        }
      $("li a").each(function() {   
          if (this.href == window.location.href) {
              $(this).addClass("active");
          }
      })
    </script>

  @endsection