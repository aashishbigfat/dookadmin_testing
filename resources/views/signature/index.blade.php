@extends('layouts.apps')
@section('headSection')
@section('title', 'Signature List')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Employee</h1>
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
                <div class="box-body" style="margin-top: 15px">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Employee Id</label> <span class="validationError" id="title_error"></span>
                            <input type="text" class="form-control" name="emp_id" id="emp_id">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Employee Name</label> <span class="validationError" id="role_error"></span>
                            <input type="text" class="form-control" name="emp_name" id="emp_name">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Employee Image</label> <span class="validationError" id="emp_img"></span>
                            <input type="file" class="form-control" name="emp_img" id="emp_img">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Employee Signature</label> <span class="validationError" id="emp_signature"></span>
                            <input type="file" class="form-control" name="emp_signature" id="emp_signature">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Designation</label> <span class="validationError" id="designation"></span>
                            <input type="text" class="form-control" name="designation" id="designation">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                            <span class="validationError" id="email"></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label>Employee Mobile</label> <span class="validationError" id="emp_mobile"></span>
                            <input type="number" class="form-control" name="emp_mobile" id="emp_mobile">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <label>Extension No</label>
                            <input type="number" class="form-control" name="extension_no" id="extension_no">
                            <span class="validationError" id="extension_no"></span>
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
                <h4>Employee List</h4>
            </div>
            <div class="dataIndex" id="dataIndex">
                @include('signature/data')
            </div>
        </div>
    </section>
</div>
<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('signature_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="emp_id">Employee ID</label>
                        <input type="text" class="form-control" name="emp_id" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="emp_name">Employee Name</label>
                        <input type="text" class="form-control" name="emp_name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Employee Email</label>
                        <input type="email" class="form-control" name="email" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <input type="text" class="form-control" name="designation" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="emp_mobile">Employee Mobile</label>
                        <input type="text" class="form-control" name="emp_mobile" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="extension_no">Extension No.</label>
                        <input type="text" class="form-control" name="extension_no" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Employee Image</label>
                        <img id="empImagePreview" src="" alt="Employee Image" style="width: 30%">
                        <input type="file" class="form-control" name="emp_image">
                    </div>
                    <div class="form-group">
                        <label>Employee Signature</label>
                        <img id="empSignaturePreview" src="" alt="Employee Signature" style="width: 30%">
                        <input type="file" class="form-control" name="emp_signature">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

    #edit_banner_image {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    #empimageold {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .exampleInputFile {
        margin-bottom: 10px
    }

    .fa-spin {
        -webkit-animation: fa-spin 2s infinite linear;
        animation: fa-spin 2s infinite linear;
    }

    .input-group-addon {
        margin-left: -42px;
        z-index: 999;
    }
</style>
@endsection
@section('footerSection')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $(".crop_wait").show();
                $(".crop_text").hide();
                
                
                var formDatas = new FormData(document.getElementById('PagesForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('signature_store') }}",
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
    //Delete Job
    $(".deletejob").click(function () {
      if (confirm("Are you sure you want to delete this Job?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/signature_delete/' + id,
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
    $(document).on('click', '.jobEdit', function() {
    // Get data attributes from the clicked element
    var id = $(this).data('id');
    var empId = $(this).data('emp_id');
    var empName = $(this).data('emp_name');
    var email = $(this).data('email');
    var designation = $(this).data('designation');
    var empMobile = $(this).data('emp_mobile');
    var extensionNo = $(this).data('extension_no');
    var empImageOld = $(this).data('emp_image_old');
    var empSignatureOld = $(this).data('emp_signature_old');

    // Populate the modal with the data
    $('#editEmployeeModal').find('input[name="emp_id"]').val(empId);
    $('#editEmployeeModal').find('input[name="emp_name"]').val(empName);
    $('#editEmployeeModal').find('input[name="email"]').val(email);
    $('#editEmployeeModal').find('input[name="designation"]').val(designation);
    $('#editEmployeeModal').find('input[name="emp_mobile"]').val(empMobile);
    $('#editEmployeeModal').find('input[name="extension_no"]').val(extensionNo);

    // Display employee image and signature (if needed for the modal)
    $('#editEmployeeModal').find('#empImagePreview').attr('src', '/signature/images/' + empImageOld);
    $('#editEmployeeModal').find('#empSignaturePreview').attr('src', '/signature/images/emp_sign/' + empSignatureOld);

    // Optionally, set the employee ID in a hidden field for form submission (if required)
    $('#editEmployeeModal').find('input[name="id"]').val(id);

    // Show the modal
    $('#editEmployeeModal').modal('show');
});


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image_show')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function triggerImage() {
        $('#empimageold').trigger('click');
    }

    function readURLBanner(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image_banner_show')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
      $('#edit_store_form').click(function (e) {
        e.preventDefault();
        $(".edit_crop_wait").show();
        $(".edit_crop_text").hide();
        var edit_id = $('#id').val(); 
        var formDatas = new FormData(document.getElementById('myEditForms'));
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "/signature_update/"+edit_id,
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

@endsection