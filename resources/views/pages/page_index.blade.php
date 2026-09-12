@extends('layouts.apps')
@section('headSection')
@section('title', 'Landing Pages')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Pages</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Landing Pages List</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a class="btn btn-primary" href="{{route('landing_pages_create')}}">Add New Page</a>
              <span class="btn btn-success">Total Pages <span style="color:#ffeb00">{{$total}}</span></span>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('pages/page_data')
            </div>
          </div>
          
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 70%">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Activities</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        <div class="row">
                          <input type="hidden" name="edit_id" id="edit_id">
                          <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="form-group">
                              <label>Page Name</label>
                              <input type="text" class="form-control" name="edit_name" id="edit_name">
                            </div>
                          </div>
                          <div class="col-md-8 col-lg-8 col-sm-12">
                            <div class="form-group">
                              <label>Title</label>
                              <input type="text" class="form-control" name="edit_title" id="edit_title">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <label>Landing Page Slug URL</label>
                              <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url">
                            </div>
                          </div>
                          <div class="col-md-8 col-lg-8 col-sm-12">
                            <div class="form-group">
                              <label>Sub Title</label>
                              <input type="text" class="form-control" name="edit_sub_title" id="edit_sub_title">
                            </div>
                          </div>
              
                          <div class="col-md-6 col-lg-6" style="margin-top: 5px">
                            <div class="form-group">
                              <label for="exampleInputFile" class="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:1080)</span></label>
                              <div class="input-group">
                                <input type="file" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorMultiple" onchange="readURL(this);">
                                <button type="button" class="btn btn-primary">Choose Banner Images</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4" id="ifImage" style="margin-top: 10px">
                              <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="120" height="80"/>
                          </div>
                           
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="edit_send_form">
                          <span class="crop_text"><i class="fa fa-edit"></i> Update</span>
                          <span class="crop_wait" style="display: none">
                         Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                        </span>
                        </button>
                        <span id="mesegess"></span>
                    </div>
                </div>
            </div>
        </form>
  </div>
  @endsection
  @section('footerSection')

  <script>
     $('.pageEdit').on('click', function() {
        $('#editModal').modal('show');
        var id = $(this).data('id');
        var name = $(this).data('name');
        var title = $(this).data('title');
        var subtitle = $(this).data('subtitle');
        var slug = $(this).data('slug');
        var image = $(this).data('image');
        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+image;
        $("#edit_id").val(id);
        $("#edit_name").val(name);
        $("#edit_title").val(title);
        $("#edit_sub_title").val(subtitle);
        $("#edit_slug_url").val(slug);
        $("#ifImage").html('<img id="image_show" onclick="triggerImage()" src="'+urlpath+'" class="" width="120" height="80"/>');
    });

     function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#image_show')
                            .attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                  }
                }


     function triggerImage(){
              $('#edit_image').trigger('click');
     } 
   </script>
   <script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                //$('#gif').show();
                var edit_id = $('#edit_id').val();
               // $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/landing-page/update/' + edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#messages').html("<span class='sussecmsg'>Successfully Update!</span>");
                        window.location = data.url;
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
@endsection