@extends('layouts.apps')
@section('headSection')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
@section('title', 'Departures List')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Departures List</h1>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Departures</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">

                        <span class="btn btn-success">Total Departure <span
                                style="color:#ffeb00">{{$total}}</span></span>
                        <form action="{{route('departures')}}" method="get" style="display: inline-flex;">
                            <div class="col-md-4 edit_dest">
                                <select class="form-control status" name="status" id="status">
                                    <option value="" <?php if($status=="no" ) { echo "selected" ; } ?>>Status..</option>
                                    <option value="1" @if($status==1 && $status !='no' ) selected="" @endif>Active
                                    </option>
                                    <option value="in" @if($status==0 && $status !='no' ) selected="" @endif>In Active
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                                <input type="text" class="form-control pull-right" name="keyword" id="keyword"
                                    placeholder="Search by destination, package.." value="{{$keywords}}">

                            </div>
                            <div class="input-group-addon">
                                <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                            </div>
                            <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="dataIndex" id="dataIndex">
                        @include('departure/departure_index_data')
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
        @csrf
        <div class="modal-dialog modal-xl" role="document" style="width: 55%">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="inlineFlax">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Optional Activities</h5>
                    </span>
                    <span class="inlineFlax" style="float: right"><button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-close"></i></button></span>
                </div>
                <div class="modal-body">
                    <div class="itinerary-setup m-t-20">
                        <div class="row">
                            <input type="hidden" name="edit_dep_id" id="dep_ids">
                            <div class="col-md-6">
                                <div class="form-group" id="optActivity">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary" id="edit_send_form"><i class="fa fa-save"></i>
                        Update</button>
                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                    <span id="mesegess"></span>
                </div>
            </div>
        </div>
    </form>
</div>
<style type="text/css">
    table.loading>tbody {
        position: relative
    }

    table.loading>tbody:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, .1);
        background-image:url("{{ asset('images/loaders.gif') }}");
        background-position: center;
        background-repeat: no-repeat;
        background-size: 65px 65px;
        content: ""
    }

    .box-header.with-border {
        border-bottom: none
    }

    a.dropdown-item.edit {
        padding-left: 10px !important;
        display: inline-block;
        padding: 5px;
    }

    .btn-group-sm>.btn,
    .btn-sm {
        padding: 1px 3px !important;
    }

    .inlineFlax {
        display: inline-flex;
    }

    .input-group-addon {
        margin-left: -42px;
        z-index: 999;
    }
</style>
@endsection
@section('footerSection')
<script>
    $('.edit-item').click(function(){
          $("#optActivity").html('');
          var id = $(this).data("id");
          $('#editModal').modal('show');
          if(id){
            $('#dep_ids').val(id);
              $.ajax({
                 type:"GET",
                 url:"{{url('/get-optional-activity-ajax')}}?departure_id="+id,
                 success:function(res){
                  if(res && res.length > 0){   
                    var html = '';
                        for(data of res){
                          html+='<div class="checkbox"><label><input id="checkedID_'+data.id+'" class="checkedID" type="checkbox" name="optional[]" value="'+data.id+'">'+data.title+'</label></div>';
                        }  
                        $("#optActivity").html(html);

                  }else{
                     $("#optActivity").empty();
                  }
                 }
              });
          }else{
              $("#optActivity").empty();
          } 
          var optional_id = $(this).attr("data-activityId");
          var opt_act_id = JSON.parse(optional_id); 
          //console.log(opt_act_id);
          if(opt_act_id != null){
          setTimeout(function()
            {
              for( var i = 0; i<opt_act_id.length; i++){
                //alert($('#checkedID_'+opt_act_id[i]).val());
                $('#checkedID_'+opt_act_id[i]).attr('checked', true);
              }
            }, 2000);
        }
        })    
</script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();

                $('#gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('dep_optional_act_ipdate') }}",
                    data: $('#myEditForm').serialize(),
                    success: function (data) {
                     // console.log(data);
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //window.location = data.url;
                        location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
</script>
<script type="text/javascript">
    $(".disableDepartue").click(function () {
          
            var status = $(this).data("status");
            var flag = status?'inactive':'active';
            if (confirm("Are you sure you want to "+flag+" this Package?"))
            
            var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
              $.ajax(
              {
                url: '/packages-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  window.location.reload();
                }
              });
          }
        });
</script>
<script type="text/javascript">
    $(".papularPackage").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to change?"))
              $.ajax(
              {
                url: '/make-featured-package/' + id,
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
            url: '?page=' + page+'&status='+'<?php echo $status;?>'+'&keyword='+'<?php echo $keywords;?>',
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
  $(".addToEMT").click(function () {
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to changes?"))
              $.ajax(
              {
                url: '/add-to-emt-package/' + id,
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
@endsection