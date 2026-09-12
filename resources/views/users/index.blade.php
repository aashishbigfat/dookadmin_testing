@extends('layouts.apps')
@section('headSection')
@section('title', 'Create User')
  <link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Create User</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create User</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777;margin-bottom: -12px;">
    <section class="content">
      <div class="row">
        <form role="form" id="UsersForm">
          @csrf
            <div class="box-body">
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>User Name</label> <span class="validationError" id="user_name_error"></span>
                  <input type="text" class="form-control" name="name" id="name">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Email</label> <span class="validationError" id="email_error"></span>
                  <input type="text" class="form-control" name="email" id="email">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label>Phone Number</label> <span class="validationError" id="phone_nember_error"></span>
                  <input type="text" class="form-control" name="phone" id="phone" oninput="this.value = (this.value.length > 12) ? this.value.slice(0,12) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1);">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group edit_dest">
                <label>Select Role</label> <span class="validationError" id="role_error"></span>
                  <select class="form-control role" name="role" id="role">
                    <option value="">Select Role..</option>
                    @foreach($roles as $role)
                      <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="box-body">
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
          <span class="userDisplay"><h3>Users List</h3></span>
          <span class="btn btn-success userDisplay text-right" style="float: right;">Total Users: <span style="color:#ffeb00">{{$total}}</span></span>
        </div>
        <div class="ItninerarListing">
          @include('users/user_data')
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