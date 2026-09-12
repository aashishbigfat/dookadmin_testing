@extends('layouts.apps')
@section('headSection')
@section('title', 'Departure | Date Wise Departure')
<link rel="stylesheet" href="{{asset('css/customCSS/departure_pointofinterest.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Date Wise Departures List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('departures')}}">Departures</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/itinerary_menu')
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <div class="box-header with-border">
          <span class="btn btn-success">Total Dates <span style="color:#ffeb00"> {{$total}}</span></span>&nbsp; &nbsp; <span style="font-size: 18px;"> {{$dep_main->title}} | {{$dep_main->no_of_nights}}N/{{$dep_main->no_of_days}}D | {{$dep_main->dep_dook_ref_id}} </span></span>
        </div>
        <div class="ItninerarListing" id="PoiListing">
          @include('departure/departure_date_data')
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}
    #map{height:180px}#searchInput{background-color:#fff;font-family:Roboto;font-size:15px;font-weight:300;margin-left:12px;padding:0 11px 0 13px;text-overflow:ellipsis;width:100%;height:35px;margin-top:0px;margin-left: 0px;}ul.dropdown-menu.inner{height:200px}.dropdown-menu.open.show{height:226px}.sussecmsg{font-size:16px;padding-left:10px;color:green}.error{color:red}.pac-container{z-index: 999999;}button.gm-control-active.gm-fullscreen-control {display: none;}.displayInline>h3{display: inline-block;margin-right: 50px;}h3#edit_title {color: red;}h3#edit_date {color: royalblue;}.padding10{padding-left: 10px !important;display: inline-block;padding: 5px;}
</style>
  @endsection
  @section('footerSection')
  <script>
    $("li a").each(function() { 
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
    var destpoi = $('#destinations').val();
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
    //       $('#DeparturePoisData').addClass('loading');
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
    //       $('#DeparturePoisData').removeClass('loading');
    //         $("#PoiListing").empty().html(data);
    //        // location.hash = page;
    //     }).fail(function(jqXHR, ajaxOptions, thrownError){
    //           alert('No response from server');
    //     });
    // }
    </script>
 
  @endsection