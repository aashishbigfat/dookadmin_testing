@extends('layouts.apps')
@section('headSection')
@section('title', 'Experiences List')

@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Experiences List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Experiences</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <span class="btn btn-success">Total Experiences <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('experience_index')}}" method="get" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($status == "no") { echo "selected"; } ?>>Status..</option>
                      <option value="1" @if($status == 1 && $status != 'no') selected="" @endif>Active</option>
                      <option value="0" @if($status == 0 && $status != 'no') selected="" @endif>In Active</option>
                  </select>
                </div>
                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by experience.." value="{{$keywords}}">
                    
                </div>
                <div class="input-group-addon">
                    <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
                <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('activityExperience/experience_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.input-group-addon {margin-left: -42px;z-index: 999;}
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
        $(".disableActivity").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");

          var flag = status?'inactive':'active';
          var token = $("meta[name='csrf-token']").attr("content");

            if (confirm("Are you sure you want to "+flag+" this Experience?"))
              $.ajax(
              {
                url: '/experiences-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
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
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
     
    </script>

  <script type="text/javascript">
    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });


    function updateOrder(data) {
      console.log(data);
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('experiencePositionReshiftings') }}",
            type:'post',
            data:{position:data},
            success:function(){
                alert('Your changes successfully saved');
                window.location.reload();
            }
        })
    }
</script>
  @endsection