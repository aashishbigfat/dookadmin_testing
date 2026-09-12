@extends('layouts.apps')
@section('headSection')
@section('title', 'Regions')
<link rel="stylesheet" href="{{asset('css/customCSS/top_destination.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Regions</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Regions</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <span style="float: left;" class="btn btn-success">Total <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('region_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by region.." value="{{$keywords}}">
                    
                </div>
                <div class="input-group-addon">
                    <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="TodDestListing" id="TodDestListing">
              @include('region/region_data_list')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.input-group-addon {margin-left: -42px;z-index: 999;
  </style>
  @endsection
  @section('footerSection')
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
          $('#topDestData').addClass('loading');
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
            url: '?page=' + page,
            type: "get",
            datatype: "html"
        }).done(function(data){
          $('#topDestData').removeClass('loading');
            $("#TodDestListing").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
  </script>
@endsection