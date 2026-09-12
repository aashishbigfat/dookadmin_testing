@extends('layouts.apps')
@section('headSection')
@section('title', 'Optional Activity')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Optional Activity List</h1>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a class="btn btn-primary" href="{{route('optional_activity_create')}}">Add New</a>
              
              <span class="btn btn-success display">Total <span style="color:#ffeb00;margin-left: 5px;">{{$total}}</span></span>
              <span class="display"><b style="margin: 5px 5px 0px 10px;">Filter by Title : </b> 
              <form action="{{route('optional_activity')}}" method="get" style="display: inline-flex;">
                
                <div class="input-group date" style="margin-right:5px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search..">
                    <div class="input-group-addon">
                        <i class="fa fa-search start-calendar"></i>
                    </div>
                </div>
               
                <div class="col-md-4 edit_dest">
                  <select class="form-control destinations" name="keyword" id="destinations">
                      
                  </select>
                </div>
              <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
              </span>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('optionalActivity/data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.display {display: inline-flex;}
  </style>
  @endsection
  @section('footerSection')
  <script>
    // Destination ajax
    $('.destinations').select2(
        {
            placeholder: 'Destination',
            ajax: {
                url: "/get-destination-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.dest_name,
                                id: item.dest_name
                            }
                        })
                    };
                },
                cache: true
            }
        });
  </script>
    <script>

        $(".disableOptionalActivity").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");
            //console.log(status);
            var token = $("meta[name='csrf-token']").attr("content");
            if(status == 1){
              confirm("Are you sure you want to disable?")
            }
            else{
              confirm("Are you sure you want to enable?")
            }
              $.ajax(
              {
                url: '/optional-activity/disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  if(data.status == 1){
                    alert("Disabled successfully!!");
                  }
                  else{
                    alert("Enabled successfully!!");
                  }
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
          $('#optionalitiListData').addClass('loading');
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
          $('#optionalitiListData').removeClass('loading');
            $("#dataIndex").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
    </script>
  @endsection