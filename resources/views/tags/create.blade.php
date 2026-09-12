@extends('layouts.apps')
@section('headSection')
@section('title', 'Add Tags')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Add Tags</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Add Tag</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="TagForm">
          @csrf
            <div class="box-body"  style="margin-top: 15px">
              <div class="col-md-4 col-lg-4">
                <div class="form-group">
                  <label>Tag Name<span class="impValidate">*</span></label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title">
                </div>
              </div>
              <div class="col-md-8 col-lg-8 button-submit text-left" style="margin-top: 25px;">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Add Tag</span>
                    <span class="crop_wait" style="display: none">
                      Please Wait <i class="fa fa-circle-o-notch fa-spin"></i> 
                    </span>
                  </button>
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>
        </form>
      </div>
      <div class="box">
        <div class="box-header with-border">
          <span style="font-size: 20px">Tags List</span><span class="btn btn-success" style="margin-left:20px">Total Tags <span style="color:#ffeb00">{{$total}}</span></span>
        </div>
        <div class="ItninerarListing">
          @include('tags/tag_list')
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="edit_tag_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myFormedittag" enctype="multipart/form-data" id="myFormedittag">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 40%">
        <div class="modal-content classes">
          <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">Tag Update <span style="float: right;"><button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button></span></h4>
          </div>
          <div class="modal-body">
            <div class="itinerary-setup m-t-20">
            <div class="days" style="margin:-10px">
            <div class="rowes">
              <input class="form-control" type="hidden" id="edit_id">
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label>Tag name</label><span class="validationError" id="edit_tag_error"></span>
                  <input class="form-control" type="text" id="edit_tag" name="edit_tag">
                </div>
              </div>
              
              <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 text-center" style="margin-top: 25px;">
                <button type="submit" class="btn btn-primary" id="edit_tag_send">
                  <span class="crop_text_edit"><i class="fa fa-save"></i> Update</span>
                  <span class="crop_wait_edit" style="display: none">                      
                   Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                 </span>
               </button>
               <span id="messages_rs"></span>
             </div>
            </div>
        </div>
      </div>
      </div>
      </div>
      </div>
    </form>
  </div>
  <style>
    .impValidate{color:#d71921;}.validationError{color:#d71921; }
  </style>
@endsection
@section('footerSection')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        
        var title = $('#title').val();
        if (title == "") {
            $(".crop_wait").hide();
            $(".crop_text").show();
            $("span#title_error").html('This field is required!');
            $("input#title").focus();
            return false;
        }
        
        var formDatas = new FormData(document.getElementById('TagForm'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{ route('tag_store') }}",
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              if(data.status == true){
                $('#mesegese').html("<span class='sussecmsg'>"+data.msg+"</span>");
                window.location.reload();
              }
              else{
                $('#mesegese').html("<span class='sussecmsg'>"+data.msg+"</span>");
                $(".crop_wait").hide();
                $(".crop_text").show();
              }
            },
            errors: function () {
            }
        });
      });
    });
  </script>
  <script type="text/javascript">
    $(".tagDelete").click(function () {
      confirm("Are you sure you want to delete this tag?")
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/delete-tag/' + id,
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
     $('.edit-tags').on('click', function() {
      $('#edit_tag_model').modal('show');
        var id = $(this).data('id');
        var name = $(this).data('name');

        $("#edit_id").val(id);
        $("#edit_tag").val(name);
      });

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#edit_tag_send').click(function (e) {
            e.preventDefault();
            $(".crop_wait_edit").show();
            $(".crop_text_edit").hide();
            var edit_id = $('#edit_id').val();
          
            var heading = $('#edit_tag').val();
            if (heading == "") {
                $("span#edit_tag_error").html('This field is required!');
                $("input#edit_tag").focus();
                return false;
            }
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: '/update-tag/' + edit_id,
                data: $('#myFormedittag').serialize(),
                success: function (data) {
                    //$('#edit_gif').hide();
                    $('#messages_rs').html("<span class='sussecmsg'>Success!</span>");
                    location.reload();
                },
                errors: function () {

                }

            });
          });
        });
      
  </script>
@endsection