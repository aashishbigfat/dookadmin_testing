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
                  <input type="text" class="form-control" name="user_name" id="user_name">
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
                  <input type="text" class="form-control" name="phone_nember" id="phone_nember" oninput="this.value = (this.value.length > 8) ? this.value.slice(0,8) : this.value; /^[0-9]+(.[0-9]{1,3})?$/.test(this.value) ? this.value : this.value = this.value.slice(0,-1);">
                </div>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group edit_dest">
                  <label>Select Role</label> <span class="validationError" id="role_error"></span>
                    <select class="form-control role" name="role" id="role">
                      <option value="">Select Role..</option>
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
    </section>
  </div>
  @endsection
  @section('footerSection')

  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var page_name = $('#page_name').val();
                if (page_name == "") {
                    $("span#page_name_error").html('This field is required!');
                    $("input#page_name").focus();
                    return false;
                }
                var title = $('#title').val();
                if (title == "") {
                  $("span#page_name_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                var formDatas = new FormData(document.getElementById('PagesForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('index') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //location.reload();
                        window.location = data.url;
                    },
                    errors: function () {

                    }

                });
            });
        });
  </script>
  @endsection