@extends('layouts.apps')
@section('headSection')
@section('title', 'Packages List')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Packages List</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Packages</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a class="btn btn-primary" href="{{route('packages_create')}}">Add New Package</a>
              
              <span class="btn btn-success">Total Packages <span style="color:#ffeb00">{{$total}}</span></span>
              <form action="{{route('packages')}}" method="get" style="display: inline-flex;">
                <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($status == "no") { echo "selected"; } ?>>Status..</option>
                      <option value="1" @if($status == 1 && $status != 'no') selected="" @endif>Active</option>
                      <option value="in" @if($status == 0 && $status != 'no') selected="" @endif>In Active</option>
                  </select>
                </div>

                {{-- <div class="col-md-4 edit_dest">
                  <select class="form-control status" name="status" id="status">
                      <option value="" <?php if($pricees == "no") { echo "selected"; } ?>>Dep. Price..</option>
                      <option value="1" @if($pricees == 1 && $pricees != 'no') selected="" @endif>With Price</option>
                      <option value="in" @if($pricees == 0 && $pricees != 'no') selected="" @endif>No Price</option>
                  </select>
                </div> --}}

                <div class="col-md-6 date" style="margin-left: 5px;width: 360px">
                    <input type="text" class="form-control pull-right" name="keyword" id="keyword" placeholder="Search by destination, package.." value="{{$keywords}}">
                    
                </div>
                <div class="input-group-addon">
                        <i class="fa fa-search start-calendar" style="margin-left: -6px;padding-top: 3px;"></i>
                </div>
              <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
              </form>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('packages/departure_index_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
<!--   <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 55%">
                <div class="modal-content">
                    <div class="modal-header">
                      <span class="inlineFlax"><h5 class="modal-title" id="exampleModalLabel">Edit Optional Activities</h5></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        <button type="submit" class="btn btn-primary" id="edit_send_form"><i class="fa fa-save"></i> Update</button>
                        <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 5%; display: none;">
                        <span id="mesegess"></span>
                    </div>
                </div>
            </div>
        </form>
    </div> -->
    <div class="modal fade" id="copyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
        @csrf
        <div class="modal-dialog modal-xl" role="document" style="width: 75%">
            <div class="modal-content">
                <div class="modal-header">
                  <span class="inlineFlax"><h5 class="modal-title" id="exampleModalLabel">Copy Package</h5></span>
                  <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-close"></i></button></span>
                </div>
                <div class="modal-body">
                <div class="itinerary-setup m-t-20">
                <div class="row">
                <input type="hidden" name="copy_id" id="copy_id">
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Package Name</label><span class="validationError" id="title_error"></span>
                    <input type="text" class="form-control" name="title" id="title">
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Package URL 1</label> <span class="validationError" id="slug_url_pre_error"></span>
                  <input type="text" class="form-control" name="slug_url_pre" id="slug_url_pre">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="form-group">
                  <label>Package URL 2</label> <span class="validationError" id="slug_url_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url" placeholder="Enter slug url">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="form-group">
                  <label>Package ID</label> <span class="validationError" id="dep_dook_ref_id_error"></span>
                  <input type="text" class="form-control" name="dep_dook_ref_id" id="dep_dook_ref_id" placeholder="Enter departure reference id" autocomplete="off">
                  <span class="validationError" id="error_package_id"></span>
                </div>
              </div>
                </div>
                </div>
              </div>
                <div class="modal-footer">
                   <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary" id="copy_send_form"><i class="fa fa-clone" aria-hidden="true"></i> Copy Package </button>
                    <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 4%; display: none;">
                    <span id="mesegess"></span>
                </div>
            </div>
        </div>
    </form>
  </div>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width: 25%;">
    
      <!-- Modal content-->
      <div class="modal-content" style="border-radius: 5px;">
        <div class="modal-body">
          <p>Are you sure you want to Publish this Itinerary to IF?</p>
        </div>
        <div class="modal-footer">
          <form method="post" name="myPublishForm" enctype="multipart/form-data" id="myPublishForm">
            @csrf
            <input type="hidden" name="publish_id" id="publish_id">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="publish_button"><i class="fa fa-upload" aria-hidden="true"></i> Yes </button>
            <img src="{{ asset('images/loader.gif') }}" id="gifs" style="width: 8%; display: none;">
            <div class="text-center">
              
              <span id="mesegese_publish"></span>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="myModalAppolyte" role="dialog">
    <div class="modal-dialog" style="width: 25%;">
    
      <!-- Modal content-->
      <div class="modal-content" style="border-radius: 5px;">
        <div class="modal-body">
          <p>Are you sure you want to Publish this Itinerary to Appolyte?</p>
        </div>
        <div class="modal-footer">
          <form method="post" name="myPublishFormAppolyte" enctype="multipart/form-data" id="myPublishFormAppolyte">
            @csrf
            <input type="hidden" name="publish_id" id="publish_id">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="publish_appolyte"><i class="fa fa-upload" aria-hidden="true"></i> Yes </button>
            <img src="{{ asset('images/loader.gif') }}" id="gifsA" style="width: 8%; display: none;">
            <div class="text-center">
              
              <span id="mesegese_publish_appolyte"></span>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.btn-group-sm>.btn, .btn-sm {padding: 1px 3px !important;}.inlineFlax{display: inline-flex;}.input-group-addon {margin-left: -42px;z-index: 999;}.validationError {color: #db2321;}.sussecmsg{color: darkgreen;}
  </style>
  @endsection
  @section('footerSection')
  <script>
    $('.copyPackage').click(function(){
      $('#copyModal').modal('show');
      var id = $(this).data('id');
     // alert(id);
      var title = $(this).data('title');
      var slug_url_pre = $(this).data('slug1');
      var slug_url = $(this).data('slug2');

      $("#copy_id").val(id);
      $("#title").val(title);
      $("#slug_url_pre").val(slug_url_pre);
      $("#slug_url").val(slug_url);
    })    
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#copy_send_form').click(function (e) {
        e.preventDefault();
        //alert("Are you sure you want to copy this package?");
        var id = $("#copy_id").val();
        $('#gif').show();

        var title = $('#title').val();
        if (title == ""){
          $('#gif').hide();
          $("span#title_error").html('This field is required!');
          $("input#title").focus();
          return false;
        }
        var slug_url_pre = $('#slug_url_pre').val();
        if (slug_url_pre == "") {
          $('#gif').hide();
          $("span#title_error").hide();
          $("span#slug_url_pre_error").html('This field is required!');
          $("input#slug_url_pre").focus();
          return false;
        }
        var slug_url = $('#slug_url').val();
        if (slug_url == "") {
          $('#gif').hide();
          $("span#slug_url_pre_error").hide();
          $("span#slug_url_error").html('This field is required!');
          $("input#slug_url").focus();
          return false;
        }
        var dep_dook_ref_id = $('#dep_dook_ref_id').val();
        if (dep_dook_ref_id == "") {
          $('#gif').hide();
          $("span#slug_url_error").hide();
          $("span#dep_dook_ref_id_error").html('This field is required!');
          $("input#dep_dook_ref_id").focus();
          return false;
        }
        $('#gif').css('visibility', 'visible');
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "/copy-package/"+id,
          data: $('#myEditForm').serialize(),
          success: function (data) {
            $('#gif').hide();
            $('#mesegess').html("<span class='sussecmsg'>Copy Successfully!</span>");
            window.location.reload();
          },
          errors: function (err) {
            $("#gif").hide();
            $('#mesegess').html("<span class='sussecmsg'>Something went wrong.!</span>");
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#dep_dook_ref_id').on('input', function(){
        var error_package_id = '';
        var pkg_id = $('#dep_dook_ref_id').val();
        //alert(pkg_id);
        var _token = $('input[name="_token"]').val();
        $.ajax({
          method: 'POST',
          url: "{{ route('dook_package_id__unique_check') }}",
          data: {pkg_id:pkg_id, _token:_token},

          success: function (result) {
            console.log(result);
            if(result == 'unique'){
              $('#error_package_id').html('<label class="text-success">Slug ID Availabel</label>');
              $('#dep_dook_ref_id').removeClass('has-error');
              $('#store_form').attr('disabled',false);
            }
            else{
              $('#error_package_id').html('<label class="text-danger">Slug ID already Exist!</label>');
              $('#dep_dook_ref_id').addClass('has-error');
              $('#store_form').attr('disabled','disabled');
            }
          },
        });
      });
    });
  </script>
    <script>
        // $('.edit-item').click(function(){
        //   $("#optActivity").html('');
        //   var id = $(this).data("id");
        //   $('#editModal').modal('show');
        //   if(id){
        //     $('#dep_ids').val(id);
        //       $.ajax({
        //          type:"GET",
        //          url:"{{url('/get-optional-activity-ajax')}}?departure_id="+id,
        //          success:function(res){
        //           if(res && res.length > 0){   
        //             var html = '';
        //                 for(data of res){
        //                   html+='<div class="checkbox"><label><input id="checkedID_'+data.id+'" class="checkedID" type="checkbox" name="optional[]" value="'+data.id+'">'+data.title+'</label></div>';
        //                 }  
        //                 $("#optActivity").html(html);

        //           }else{
        //              $("#optActivity").empty();
        //           }
        //          }
        //       });
        //   }else{
        //       $("#optActivity").empty();
        //   } 
        //   var optional_id = $(this).attr("data-activityId");
        //   var opt_act_id = JSON.parse(optional_id); 
        //   if(opt_act_id != null){
        //   setTimeout(function()
        //     {
        //       for( var i = 0; i<opt_act_id.length; i++){
        //         $('#checkedID_'+opt_act_id[i]).attr('checked', true);
        //       }
        //     }, 2000);
        // }
        // })    
    </script>
    <script type="text/javascript">
      // $(document).ready(function () {
      //       $('#edit_send_form').click(function (e) {
      //           e.preventDefault();
      //           $('#gif').show();

      //           $('#gif').css('visibility', 'visible');
      //           $.ajax({
      //               headers: {
      //                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      //               },
      //               method: 'POST',
      //               url: "{{ route('dep_optional_act_ipdate') }}",
      //               data: $('#myEditForm').serialize(),
      //               success: function (data) {
      //                   $('#gif').hide();
      //                   $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
      //                   location.reload();
      //               },
      //               errors: function () {

      //               }

      //           });
      //       });
      //   });
    </script>
    <script type="text/javascript">
        $(".disableDepartue").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Package?"))
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
        });
    </script>
    <script type="text/javascript">
        $(".gcccDepartue").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Package On GCC Server?"))
              $.ajax(
              {
                url: '/gccpackages-disable/' + id,
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
    <script type="text/javascript">
        $(".papularPackage").click(function () {
          if (confirm("Are you sure you want to change?"))
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
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
            }
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
  $(".publishItinerary").click(function () {
    //var flag = status?'inactive':'active';
    //if (confirm("Are you sure you want to Publish this Itinerary?"))
    $("#myModal").modal('show');
    var id = $(this).data("id");
    $("#publish_id").val(id);

  });
   $(document).ready(function () {
      $('#publish_button').click(function (e) {
      e.preventDefault();
      var id = $("#publish_id").val();
      $('#gifs').show();
      
      $('#gifs').css('visibility', 'visible');
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: '/publish-itinearary/' + id,
        data: $('#myPublishForm').serialize(),
        success: function (data) {
          if(data.msg_s){
            $("#gifs").hide();
            $('#mesegese_publish').html("<span class='sussecmsg'>Itinerary has been published!</span>");
            window.location.reload();
          }else{
            $("#gifs").hide();
            $('#mesegese_publish').html("<span class='sussecmsgWrong'>Something went Wrong!</span>");
            
          }
        }
      });
    })
  });
</script>
<script type="text/javascript">
  $(".publishAppolyte").click(function () {
    $("#myModalAppolyte").modal('show');
    var id = $(this).data("id");
    $("#publish_id").val(id);

  });
   $(document).ready(function () {
      $('#publish_appolyte').click(function (e) {
      e.preventDefault();
      var id = $("#publish_id").val();
      $('#gifs').show();
      
      $('#gifsA').css('visibility', 'visible');
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: '/publish-itinearary-appolyte/' + id,
        data: $('#myPublishFormAppolyte').serialize(),
        success: function (data) {
          if(data.msg_s){
            $("#gifsA").hide();
            $('#mesegese_publish_appolyte').html("<span class='sussecmsg'>Itinerary has been published!</span>");
            window.location.reload();
          }else{
            $("#gifsA").hide();
            $('#mesegese_publish_appolyte').html("<span class='sussecmsgWrong'>Something went Wrong!</span>");
            
          }
        }
      });
    })
  });
</script>
<script type="text/javascript">
  $(".addToEMT").click(function () {
    if (confirm("Are you sure you want to change?"))
    var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
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
      }
  });
</script>
  @endsection