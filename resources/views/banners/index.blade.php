
@extends('layouts.apps')
@section('headSection')
@section('title', 'Banners')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Banners</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Banners List</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a class="btn btn-primary" href="{{route('banner_create')}}">Add New Banner</a>
              <span class="btn btn-success">Total Banners <span style="color:#ffeb00">{{$total}}</span></span>
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
              @include('banners/data')
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
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Banner</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        <div class="row">
                          <input type="hidden" name="edit_id" id="edit_id">
                          <div class="col-md-4 col-lg-4 col-sm-12">
			                <div class="form-group">
			                  <label>Banner Title<span class="impValidate">*</span></label>
			                  <input type="text" class="form-control" name="edit_title" id="edit_title">
			                </div>
			              </div>
			              <div class="col-md-4 col-lg-4 col-sm-12">
			                <div class="form-group">
			                  <label>Banner Sub Title</label>
			                  <input type="text" class="form-control" name="edit_sub_title" id="edit_sub_title">
			                </div>
			              </div>
			              <div class="col-md-4 col-lg-4 col-sm-12">
			                <div class="form-group">
			                  <label>URL<span class="impValidate">*</span></label>
			                  <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url">
			                </div>
			              </div>
			              <div class="col-md-4 col-lg-4 col-sm-12">
			                <div class="form-group">
			                  <label>Days<span class="impValidate">*</span></label>
			                  <input type="text" class="form-control" name="edit_days" id="edit_days">
			                </div>
			              </div>
			              <div class="col-md-3 col-lg-3 col-sm-12">
			                <div class="form-group">
			                  <label>Where to Show<span class="impValidate">*</span></label> <span class="validationError" id="where_show_error"></span>
			                  <select class="form-control where_show" name="where_show" id="where_show">
			                    <option value=""> Select Module</option>
			                    <option value="depature">Depature</option>
			                    <option value="experience"> Experience</option>
			                    <option value="experience_single"> Experience Single</option>
			                    <option value="destination"> Destination</option>
			                    <option value="activity">Activity</option>
                          <option value="region">Region</option>
                          <option value="region_single">Region Single</option>
			                  </select>
			                </div>
			              </div>
			              <div class="col-md-12 col-lg-12 col-sm-12">
			                <div class="form-group">
			                  <label>Banner Description<span class="impValidate">*</span></label> <span class="validationError" id="description_error"></span>
			                  <textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px"> </textarea>
			                </div>
			              </div>
                          <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                            <div class="form-group">
			                  <label class="fbm_images" for="exampleInputFile">Banner Image<span class="impValidate">*</span> <span style="color: #9a191e">(W:1250, H:520)</span></label>
			                  <div class="input-group" style="margin-top:15px">
			                  <input type="file" id="edit_image" name="edit_image" accept="image/jpeg, image/jpg, image/png" onchange="readURL(this);">
			                  <button type="button" class="btn btn-primary">Choose Banner Images</button>
			                  </div>
			                </div>
                          </div>
                          <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                              <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="120" height="80"/>
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
   $('.bannerEdit').on('click', function() {
    $('#editModal').modal('show');

    var id = $(this).data('id');
    var title = $(this).data('title');
    var subtitle = $(this).data('subtitle');
    var slug = $(this).data('slug');
    var days = $(this).data('days');
    var description = $(this).data('description');
    var whereshow = $(this).data('whereshow');
    var image = $(this).data('image'); // already a full signed URL

    $("#edit_id").val(id);
    $("#edit_title").val(title);
    $("#edit_sub_title").val(subtitle);
    $("#edit_slug_url").val(slug);
    $("#edit_days").val(days);
    $("#edit_description").val(description);
    
    $("#editModal").find("select[name='where_show'] option[value='"+whereshow+"']").attr('selected', 'selected');

    // Directly use the full image URL
    $("#ifImage").html('<img id="blah" onclick="triggerImage()" src="'+image+'" class="" width="120" height="80"/>');
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
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
                var edit_id = $('#edit_id').val();
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "banners/update/"+edit_id,
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