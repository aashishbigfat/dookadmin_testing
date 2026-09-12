@extends('layouts.apps')
@section('headSection')
@section('title', 'Footer')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Footer Section</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="FooterForm">
          @csrf
            <div class="box-body">
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Opening Day</label>
                  <input type="text" class="form-control" name="opening_day" id="opening_day" value="{{$footer->opening_day}}">
                  <input type="hidden" class="form-control" id="footerId" value="{{$footer->id}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Opening time</label>
                  <input type="text" class="form-control" name="opening_timing" id="opening_timing" value="{{$footer->opening_timing}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Phone (Landline)</label>
                  <input type="text" class="form-control" name="phone" id="phone" value="{{$footer->phone}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>WhatsApp</label>
                  <input type="text" class="form-control" name="mobile" id="mobile" value="{{$footer->mobile}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control" name="email" id="email" value="{{$footer->email}}">
                </div>
              </div>

              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Header Phone</label>
                  <input type="text" class="form-control" name="header_phone" id="header_phone" value="{{$footer->header_phone}}">
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <label>Header Email</label>
                  <input type="text" class="form-control" name="header_email" id="header_email" value="{{$footer->header_email}}">
                </div>
              </div>
              <!-- <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Follow Us</label>
                  <textarea class="form-control" name="follow_us" id="follow_us" style="height: 200px"> </textarea>
                </div>
              </div> -->
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>Address</label>
                  <textarea class="form-control" name="address" id="address" style="height: 200px">{{$footer->address}} </textarea>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>About Description</label>
                  <textarea class="form-control" name="description" id="description" style="height: 200px">{{$footer->about}} </textarea>
                </div>
              </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                    <span class="crop_wait" style="display: none">
                      Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                    </span>
                  </button>
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
    </section>
  </div>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#store_form').click(function (e) {
          e.preventDefault();
          var id = $('#footerId').val();
          $(".crop_wait").show();
          $(".crop_text").hide();
          var formDatas = new FormData(document.getElementById('FooterForm'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "/footer/update/"+id,
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                  $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                  window.location = data.url;
              },
              errors: function () {

              }

          });
      });
    });
  </script>
  @endsection