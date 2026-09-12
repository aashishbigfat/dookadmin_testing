@extends('layouts.apps')
@section('headSection')
@section('title', 'Package Image Compression')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Package Image Compression</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Image Compression</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <div class="box-body" style="margin-top: 30px;">
          <div class="col-md-3 col-lg-3 col-sm-3 button-submit text-left">
            <form role="form" id="4imageCompress">
            @csrf
              <button class="btn btn-primary active" type="button" id="multi_store_form">
                <span class="crop_text"><i class="fa fa-save"></i> Package 4 Image Compress </span>
                <span class="crop_wait" style="display: none">
                  Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                </span>
                <sup class="label label-success badgeTotals">{{$count4img}}</sup>
              </button>
            </form>
          </div>
          <div class="col-md-3 col-lg-3 col-sm-3 button-submit text-left">
            <form role="form" id="featuredimageCompress">
              @csrf
              <button class="btn btn-primary active" type="button" id="store_form">
                <span class="crop_text1"><i class="fa fa-save"></i> Featured Image Compress</span>
                <span class="crop_wait1" style="display: none">
                  Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                </span>
                <sup class="label label-success badgeTotals">{{$countFimg}}</sup>
              </button>
            </form> 
          </div>
          <div class="col-md-3 col-lg-3 col-sm-3 button-submit text-left">
            <form role="form" id="bannerimageCompress">
              @csrf
              <button class="btn btn-primary active" type="button" id="banner_store_form">
                <span class="crop_text2"><i class="fa fa-save"></i> Banner Image Compress</span>
                <span class="crop_wait2" style="display: none">
                  Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                </span>
                <sup class="label label-success badgeTotals">{{$countBimg}}</sup>
              </button>
            </form> 
          </div>
          <div class="col-md-12">
            <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
          </div>
        </div>
      </div>
    </section>
  </div>
  <style>
    .impValidate{color:#d71921;}.validationError{color:#d71921; }.badgeTotals{position: absolute !important;top: -23px !important;text-align: center;font-size: 15px;padding: 4px 5px;line-height: .9;}
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#multi_store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        var formDatas = new FormData(document.getElementById('4imageCompress'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('four_image_compress_update') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $(".crop_wait").hide();
            $(".crop_text").show();
            $('#mesegese').html("<span class='sussecmsg'>Bingo Success!</span>");
            location.reload();
          },
          errors: function () {
            $(".crop_wait1").hide();
            $(".crop_text1").show();            
            $('#mesegese').html("<span class='sussecmsg'>'"+data.url+"'</span>");
          }
        });
      });
    });

    // @image compress

    $(document).ready(function () {
      $('#store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait1").show();
        $(".crop_text1").hide();
        var formDatas = new FormData(document.getElementById('featuredimageCompress'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('featured_image_compress_update') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $(".crop_wait1").hide();
            $(".crop_text1").show();            
            $('#mesegese').html("<span class='sussecmsg'>Bingo Success!</span>");
            location.reload();
          },
          errors: function () {
            $(".crop_wait1").hide();
            $(".crop_text1").show();            
            $('#mesegese').html("<span class='sussecmsg'>'"+data.url+"'</span>");
          }
        });
      });
    });

    // Banner image compress

    $(document).ready(function () {
      $('#banner_store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait2").show();
        $(".crop_text2").hide();
        var formDatas = new FormData(document.getElementById('bannerimageCompress'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('banner_image_compress_update') }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $(".crop_wait2").hide();
            $(".crop_text2").show();            
            $('#mesegese').html("<span class='sussecmsg'>Bingo Success!</span>");
            location.reload();
          },
          errors: function () {
            $(".crop_wait2").hide();
            $(".crop_text2").show();            
            $('#mesegese').html("<span class='sussecmsg'>'"+data.url+"'</span>");
          }
        });
      });
    });
  </script>
  @endsection