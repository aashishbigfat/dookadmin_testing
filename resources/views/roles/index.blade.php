@extends('layouts.apps')
@section('headSection')
@section('title', 'Role Details')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <!-- <section class="content-header">
      <h1>Roles</h1>
    </section> -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <span class="userDisplay"><h3>Roles</h3></span>
          <span class="userDisplay" style="float: right;"><a class="btn btn-primary" href="{{route('banner_create')}}"><i class="fa fa-plus" aria-hidden="true"></i> Add New Role</a></span>
        </div>
        <div class="ItninerarListing">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="panel-group" id="accordion">
                  <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                      <h4 class="panel-title">
                        <span class="glyphicon glyphicon-file"></span>
                        POST NEW ARTICLE
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox">
                                  Checkbox 1
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="ediUsersModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myEditForm" enctype="multipart/form-data" id="myEditForm">
            <div class="modal-dialog modal-xl" role="document" style="width: 45%">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom: 2px solid darkgrey;">
                      <span class="inlineFlax"><h3 class="modal-title" id="exampleModalLabel">User Edit</h3></span>
                      <span class="inlineFlax" style="float: right"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-close"></i></button></span>
                    </div>
                    <div class="modal-body">
                      <div class="itinerary-setup m-t-20">
                        <div class="row">
                          <input type="hidden" name="edit_id" id="edit_id">
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>User Name</label>
                              <input type="text" class="form-control" name="edit_name" id="edit_name">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Email</label>
                              <input type="text" class="form-control" name="edit_email" id="edit_email" disabled="">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <label>Phone</label>
                              <input type="text" class="form-control" name="edit_phone" id="edit_phone">
                            </div>
                          </div>
                          <div class="col-md-6 col-lg-6 col-xl-6">
                            <div class="form-group edit_dest">
                            <label>Select Role</label> <span class="validationError" id="role_error"></span>
                              <select class="form-control edit_role" name="edit_role" id="edit_role">
                                @foreach($roles as $role)
                                  <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="edit_send_form">
                          <span class="crop_text_edit"><i class="fa fa-edit"></i> Update</span>
                          <span class="crop_wait_edit" style="display: none">                      
                          <i class="fa fa-circle-o-notch fa-spin"></i> Wait
                        </span>
                        </button>
                        <span id="mesegeses"></span>
                    </div>
                </div>
            </div>
        </form>
  </div>
  @endsection
  @section('footerSection')

  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var formDatas = new FormData(document.getElementById('UsersForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{route('user_store')}}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#mesegese').html("<span class='sussecmsg'>"+data+"</span>");
                        location.reload();
                        //window.location = data.url;
                    },
                    errors: function () {

                    }
                });
            });
        });
  </script>
  <script>
    $('.ediUsers').on('click', function() {
      $('#ediUsersModel').modal('show');
      var id = $(this).data('id');
      console.log(id);
      var name = $(this).attr("data-name");
      //console.log(name);
      var email = $(this).attr("data-email");
      //console.log(email);
      var phone = $(this).attr("data-phone");
      var role_id = $(this).data("role_id");
      var role_name = $(this).data("role_name");

      $("#edit_id").val(id);
      $("#edit_name").val(name);
      $("#edit_email").val(email);
      $("#edit_phone").val(phone);
      $("#ediUsersModel").find("select[name='edit_role'] option[value='"+role_id+"']").attr('selected', 'selected');   
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#edit_send_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait_edit").show();
                $(".crop_text_edit").hide();
                var edit_id = $('#edit_id').val();
                var formDatas = new FormData(document.getElementById('myEditForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/users/update/' + edit_id,
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      //console.log(data);
                      $('#mesegeses').html("<span class='sussecmsg'>"+data+"</span>");
                      location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <!-- Disable users -->
  <script type="text/javascript">
        $(".disableUsers").click(function () {
          var id = $(this).data("id");
          var status = $(this).data("status");
           var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this User?"))
              $.ajax(
              {
                url: '/users/delete/' + id,
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