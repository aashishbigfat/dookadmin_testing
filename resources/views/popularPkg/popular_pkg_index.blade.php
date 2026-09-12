@extends('layouts.apps')
@section('headSection')
@section('title', 'Popular Packages List')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Popular Packages List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Popular Packages</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <!-- <a class="btn btn-primary" href="{{route('packages_create')}}">Add New Package</a> -->
              
              <span class="btn btn-success">Total Packages <span style="color:#ffeb00">{{$total}}</span></span>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('popularPkg/popular_pkg_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.btn-group-sm>.btn, .btn-sm {padding: 1px 3px !important;}.inlineFlax{display: inline-flex;}
  </style>
  @endsection
  @section('footerSection')
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");
          var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Package?"))
              $.ajax(
              {
                url: '/popular-package-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Package disabled successfully!!");
                  window.location.reload();
                }
              });
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
          $('#departureListData').addClass('loading');
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
          $('#departureListData').removeClass('loading');
            $("#dataIndex").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
    </script>

    <script type="text/javascript">
        $(".removePapularPackage").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to remove?"))
              $.ajax(
              {
                url: '/most-papular-package/' + id,
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
        });
    </script>
  @endsection