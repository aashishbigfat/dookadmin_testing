@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook | Mailer Send')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Mailer Create</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mailer</li>
      </ol>
    </section>
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
              <span class="btn btn-success">Total Mailers<sup><span style="color:#ffeb00"> {{$total}}</span></sup></span>
              <form method="get" action="{{route('index_mailer')}}" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                    <select class="form-control filterMailer" name="month">
                        <option value="">Select Month</option>
                        @foreach($months as $month)
                            <option value="{{$month->month}}" @if($monthN == $month->month) selected @endif>{{$month->month}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 edit_dest">
                    <select class="form-control filterMailer" name="year">
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{$year->year}}" @if($yearN == $year->year) selected @endif>{{$year->year}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control filterMailer" placeholder="Search..." value="{{$keyword}}" name="keyword">
                    
                </div>
              <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <form role="form" id="mailerForm">
            @csrf
                <div class="box">
                    <div class="box-body boxBodyPadding">
                        <div class="col-md-5 col-lg-5 col-xl-5 col-sm-12 col-xs-12">
                          <div class="form-group">
                            <label style="padding-bottom: 10px;">Select Html File</label>
                            <input type="file" id="html_file" name="html_file" class="form-control-file">
                          </div>
                        </div>
                        <div class="col-md-5 col-lg-5 col-xl-5 col-sm-12 col-xs-12">
                          <div class="form-group">
                            <label style="padding-bottom: 10px;">Select Image</label> <span class="validationError" id="heading_error"></span>
                            <input type="file" id="image_file" name="image_file[]" class="form-control-file" multiple>
                          </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-2">
                            <button class="btn btn-primary active" type="button" id="store_form"> <i class="fa fa-save"></i> Upload
                            </button>
                            <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="box">
        <div class="box-header with-border">
          <h4>Mailers List</h4>
        </div>
        <div class="ItninerarListing">
            @include('mailer/mailer_list')
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    .boxBodyPadding{
      padding-top: 50px;
      padding-bottom: 30px;
    }
    
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">

        jQuery('#store_form').click(function (e) {
            e.preventDefault();
            jQuery('#store_form').html('Please wait...')
            jQuery('#store_form').prop('disabled', true);
            var htmlF = $('#html_file').val();
            var imageF = $('#image_file').val();
            if (htmlF == "" && imageF  == "") {
                $("#mesegese").html('Select atleast 1 field!');
                $("#mesegese").focus();
                return false;
            }
            var formDatas = new FormData(document.getElementById('mailerForm'));
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{route('mailer_store')}}",
                data: formDatas,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#store_form_save').prop('disabled', true);
                    if(data.msg){
                        $('#mesegese').html("<span class='sussecmsg'>"+data.msg+"</span>");
                    }else{
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location.reload();
                    }
                    
                },
                errors: function () {
                    jQuery('.gif1').hide();
                    jQuery('#mesegese').html("<span class='sussecmsg'>Something went wrong!</span>");
                }

            });
        });
    </script> 
  @endsection