@extends('layouts.apps')
@section('headSection')
@section('title', 'Optional Activity')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Optional Activity Create</h1>
    </section>
    <section class="content">
      <div class="row">
          <form role="form" id="AgentItineraryForm" enctype="multipart/form-data">
            @csrf
              <div class="box-body">
                <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                  <div class="form-group dest_add">
                    <label>Destination</label> <span class="validationError" id="destination_error"></span>
                    <select class="form-control destination" name="destination" id="destination">
                                                
                    </select>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Title</label> <span class="validationError" id="title_error"></span>
                    <input type="text" class="form-control" name="title" id="title">
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Duration</label> <span class="validationError" id="duration_error"></span>
                    <input type="text" class="form-control" name="duration" id="duration">
                  </div>
                </div>
                
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Description</label> <span class="validationError" id="description_error"></span>
                    <textarea class="form-control" name="description" id="description"></textarea>
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Price</label><span class="validationError" id="price_error"></span>
                    <textarea class="form-control" name="price" id="price"></textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-group">
                  <label for="exampleInputFile">Choose Image</label> <span class="validationError" id="image_error"></span> 
                  <input type="file" id="image" name="image_name" onchange="readURL(this);" accept="image/*">
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                  <img id="blah" onclick="triggerImage()" src="{{{asset('images/no-image.png')}}}" class="" width="80" height="50"/>
              </div>
               
                <div class="col-md-12 col-lg-12 col-sm-12 button-submit">
                  <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Submit</button>
                  <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                  <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                </div> 
              </div>
          </form>
      </div>
    </section>
  </div>
  <!-- Edit Itinearay Modal-->
    <style type="text/css">
    .steps.clearfix{margin-top:10px}span.step-icon{padding-top:10px}.steps.clearfix>ul>li{display:inline-flex;margin-right:20px}.box.box-primary{border-top-color:#3c8dbc;background:0 0}.radio{display:inline}.radio>label{margin-right:30px}.validationError {color: #ff0c0c;}.button-submit{margin-top: 20px;margin-bottom: 20px}.ck.ck-content.ck-editor__editable {height: 150px;}span.ck-file-dialog-button {display: none;}.steps.clearfix.text-center{margin-top: 20px;padding-bottom: 20px;}.dest_add span#select2-destination-container {padding: 0px 0px 0px 0px; line-height: 1.6;}
  </style>
  @endsection
  @section('footerSection')

  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();

                var destinationName = $('#destination').val();
                if (destinationName == "") {
                    $("span#destination_error").html('This field is required!');
                    $("select#destination").focus();
                    return false;
                }
                var title = $('#title').val();
                if (title == "") {
                  $("span#destination_error").hide();
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }
                
                var image = $('#image').val();
                if (image == "") {
                    $("span#title_error").hide();
                    $("span#image_error").html('This field is required!');
                    $("input#image").focus();
                    return false;
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('AgentItineraryForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('optional_activity_store') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location = data.url;
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <script>
    $('.destination').select2({
            placeholder: 'Select Destination',
            ajax: {
                url: "/destination-for-optional-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.dest_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
  </script>
  <script>
    $(document).ready(function() {
      $('#description').summernote({
          height: 150,
          focus: true
      });
    });

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
              $('#image').trigger('click');
     }    
  </script>
  <script>
    $(document).ready(function() {
    $("#price").keypress(function (e) {
          if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#price_error").html("Digits Only").
            show().fadeOut(3000);
            return false;
          }
      });
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  @endsection