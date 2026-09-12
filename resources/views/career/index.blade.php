@extends('layouts.apps')
@section('headSection')
@section('title', 'Job Create | Update')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Job</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="PagesForm">
          @csrf
            <div class="box-body"  style="margin-top: 15px">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Title</label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="title" id="title">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Role</label> <span class="validationError" id="role_error"></span>
                  <input type="text" class="form-control" name="role" id="role">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Slug Url</label> <span class="validationError" id="slug_error"></span>
                  <input type="text" class="form-control" name="slug_url" id="slug_url">
                </div>
              </div>
                <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Location</label> <span class="validationError" id="location_error"></span>
                    <input type="text" class="form-control" name="location" id="location">
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>No. of Positions</label> 
                    <input type="text" class="form-control" name="position" id="position">
                    <span class="validationError" id="position_error"></span>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-12" style="margin-left: -15px;">
                  <div class="form-group">
                    <label>Experience</label> 
                    <input type="text" class="form-control" name="experience" id="experience">
                    <span class="validationError" id="experience_error"></span>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2">
                  <div class="form-group">
                    <label>Status</label>
                    <br>
                    <select class="form-control status" name="status" id="status">
                      <option value="1">Open</option>
                      <option value="0">Close</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2">
                  <div class="form-group">
                    <label>Job for</label>
                    <br>
                    <select class="form-control type" name="type" id="type">
                      <option value="Dook">Dook</option>
                      <option value="Wat">Wat</option>
                    </select>
                  </div>
                </div>
              <div class="col-md-12 col-lg-12 col-xl-12" style="margin-left: -15px;">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="description"></textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
             <div class="col-md-12 col-lg-12 col-sm-12">
              <h3>Meta Informations</h3>
              <hr style="border-bottom: 2px solid #777">
             </div>  
              
            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Title</label> 
                  <textarea class="form-control" name="meta_title" id="meta_title"> </textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Keywords</label>
                  <textarea class="form-control" name="meta_keywords" id="meta_keywords"></textarea>
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Meta Description</label>
                  <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
                </div>
              </div>
            </div>
            <div class="box-body">
              <!--  -->
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Save</span>
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
          <h4>Job Post List</h4>
        </div>
        <div class="dataIndex" id="dataIndex">
          @include('career/data')
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForms">
      @csrf
      <div class="modal-dialog modal-xl" role="document" style="width: 70%">
          <div class="modal-content">
              <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">Edit Job</h3></span>
                <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fa fa-close"></i></button></span>
              </div>
              <div class="modal-body">
                <div class="itinerary-setup m-t-20">
                  <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Title</label> <span class="validationError" id="edit_title_error"></span>
                      <input type="text" class="form-control" name="edit_title" id="edit_title">
                      <input type="hidden" class="form-control" name="edit_id" id="edit_id">
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Role</label> <span class="validationError" id="edit_role_error"></span>
                      <input type="text" class="form-control" name="edit_role" id="edit_role">
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Slug Url</label> <span class="validationError" id="edit_slug_error"></span>
                      <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url">
                    </div>
                  </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <label>Location</label> <span class="validationError" id="edit_location_error"></span>
                        <input type="text" class="form-control" name="edit_location" id="edit_location">
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group">
                        <label>No. of Positions</label> 
                        <input type="text" class="form-control" name="edit_position" id="edit_position">
                        <span class="validationError" id="edit_position_error"></span>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12" style="margin-left: -15px;">
                      <div class="form-group">
                        <label>Experience</label> 
                        <input type="text" class="form-control" name="edit_experience" id="edit_experience">
                        <span class="validationError" id="edit_experience_error"></span>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group">
                        <label>Status</label>
                        <br>
                        <select class="form-control edit_status" name="edit_status" id="edit_status">
                          <option value="1">Open</option>
                          <option value="0">Close</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group">
                        <label>Job for</label>
                        <br>
                        <select class="form-control edit_type" name="edit_type" id="edit_type">
                          <option value="Dook">Dook</option>
                          <option value="Wat">Wat</option>
                        </select>
                      </div>
                    </div>
                  <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                      <label>Description</label>
                      <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                    </div>
                  </div>
                   
                </div>
                <div class="row">
                 <div class="col-md-12 col-lg-12 col-sm-12">
                  <h3>Meta Informations</h3>
                  <hr style="border-bottom: 2px solid #777">
                 </div>  
                  
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="edit_meta_title" id="edit_meta_title"> </textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="edit_meta_keywords" id="edit_meta_keywords"></textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="edit_meta_description" id="edit_meta_description"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="text-align: left;">
              <button type="submit" class="btn btn-primary" id="edit_store_form">
                <span class="edit_crop_text"><i class="fa fa-edit"></i> Update</span>
                <span class="edit_crop_wait" style="display: none">
               Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
              </span>
              </button>
              <span id="edit_mesegess"></span>
            </div>
          </div>
      </div>
    </form>
  </div>
  <style type="text/css">
        /*.box-footer{background-color: #ecf0f5;}*/
  </style>
  @endsection
  @section('footerSection')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $('#title').on("change keyup paste click", function() {
      var Text = $(this).val();
      Text = Text.toLowerCase();
      Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
      $('#slug_url').val(Text);
    });
    $('#edit_title').on("change keyup paste click", function() {
      var Text = $(this).val();
      Text = Text.toLowerCase();
      Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
      $('#edit_slug_url').val(Text);
    });
  </script>
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
                var role = $('#role').val();
                if (role == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#title_error").hide();
                    $("span#role_error").html('This field is required!');
                    $("input#role").focus();
                    return false;
                }
                var location = $('#location').val();
                if (location == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#role_error").hide();
                    $("span#location_error").html('This field is required!');
                    $("input#location").focus();
                    return false;
                }
                var position = $('#position').val();
                if (position == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#location_error").hide();
                    $("span#position_error").html('This field is required!');
                    $("input#position").focus();
                    return false;
                }
                var experience = $('#experience').val();
                if (experience == "") {
                  $(".crop_wait").hide();
                  $(".crop_text").show();
                  $("span#position_error").hide();
                    $("span#experience_error").html('This field is required!');
                    $("input#experience").focus();
                    return false;
                }
                var formDatas = new FormData(document.getElementById('PagesForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('job_store') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                      //window.location = data.url;
                      window.location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
  </script>
  <script type="text/javascript">
    $(".disablejob").click(function () {
      var status = $(this).data("status");
      var flag = status?'close':'open';
      if (confirm("Are you sure you want to "+flag+" this Job?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/job_status_change/' + id,
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
    //Delete Job
    $(".deletejob").click(function () {
      if (confirm("Are you sure you want to delete this Job?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/job_delete/' + id,
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
    $('.jobEdit').on('click', function() {
      $('#editModal').modal('show');
      var id = $(this).data('id');
      var title = $(this).data('title');
      var role = $(this).data('role');
      var slug = $(this).data('slug');
      var location = $(this).data('location');
      var description = $(this).data('description');
      var position = $(this).data('position');
      var experience = $(this).data('exp');
      var status = $(this).data('status');
      var meta_title = $(this).data('meta_title');
      var meta_keywords = $(this).data('meta_keywords');
      var meta_description = $(this).data('meta_description');
      var type = $(this).data('type');
      $("#edit_id").val(id);
      $("#edit_title").val(title);
      $("#edit_role").val(role);
      $("#edit_slug_url").val(slug);
      $("#edit_location").val(location);
      $("#edit_position").val(position);
      $("#edit_experience").val(experience);
      $("#edit_meta_title").val(meta_title);
      $("#edit_meta_keywords").val(meta_keywords);
      $("#edit_meta_description").val(meta_description);
      $("#editModal").find("select[name='edit_status'] option[value='"+status+"']").attr('selected', 'selected');
      $("#editModal").find("select[name='edit_type'] option[value='"+type+"']").attr('selected', 'selected');
      $("#edit_description").summernote('code', description);
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#edit_store_form').click(function (e) {
        e.preventDefault();
        $(".edit_crop_wait").show();
        $(".edit_crop_text").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('myEditForms'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "/manage_job_update/"+edit_id,
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
            $('#edit_messages').html("<span class='sussecmsg'>Successfully Updated!</span>");
            window.location.reload();
          },
          errors: function () {
            $(".edit_crop_wait").hide();
            $(".edit_crop_text").show();
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
          onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
            var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            var div = $('<div />');
            div.append(bufferText);
            div.find('*').removeAttr('style');
            setTimeout(function () {
            if(bufferText){
              document.execCommand('insertHtml', false, div.html());
            }else{
              document.execCommand('insertText', false, bufferText1);
            }
            }, 10);
          }
        },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:220,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#edit_description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
          onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
            var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            var div = $('<div />');
            div.append(bufferText);
            div.find('*').removeAttr('style');
            setTimeout(function () {
            if(bufferText){
              document.execCommand('insertHtml', false, div.html());
            }else{
              document.execCommand('insertText', false, bufferText1);
            }
            }, 10);
          }
        },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:220,
        focus: true
      });
    });
  </script>
  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#page_banner').trigger('click');
    } 
  </script>
  <script>
    // $("#role").keyup(function(){
    //   var Text = $(this).val();
    //   Text = Text.toLowerCase();
    //   Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
    //   $("#slug_url").val(Text);        
    // });
    // //Edit Page
    // $("#edit_role").keyup(function(){
    //   var Text = $(this).val();
    //   Text = Text.toLowerCase();
    //   Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
    //   $("#edit_slug_url").val(Text);        
    // });
  </script>

  @endsection