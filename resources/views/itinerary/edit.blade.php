@extends('layouts.apps')
@section('headSection')
@section('title', 'Itinerary Edit')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Itinerary Edit</h1>
    </section>
    <section class="content">
      <div class="row">
          <form role="form" id="AgentItineraryForm" enctype="multipart/form-data">
            @csrf
              <div class="box-body">
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Title</label> <span class="validationError" id="title_error"></span>
                    <input type="text" class="form-control" name="title" id="title" value="{{$agentData->title}}">
                  </div>
                </div>
                
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Terms & Conditions</label> <span class="validationError" id="description_error"></span>
                    <textarea class="form-control" name="description" id="description">{!! $agentData->description !!}</textarea>
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label for="exampleInputFile">Attachment</label> <span class="validationError" id="image_error"></span> 
                  <input type="file" id="pdf_name" name="pdf_name" accept="application/pdf" onchange="readURL(this);">
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12" style="margin-top: 20px">
                <?php if($agentData->pdf_file == null) { ?>
                  <span id="blah" onclick="triggerImage()"><button style="font-size:24px"><i class="fa fa-file-pdf-o"></i> No File</button></span>
                <?php } else {?>
                  <span id="blah" onclick="triggerImage()"><a href="{{asset('agentitinerary/'.$agentData->pdf_file)}}"><i class="fa fa-file-pdf-o" style="font-size:30px;color:red"></i> {{$agentData->pdf_file}}</a></span>
                <?php } ?>
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
    .steps.clearfix{margin-top:10px}span.step-icon{padding-top:10px}.steps.clearfix>ul>li{display:inline-flex;margin-right:20px}.box.box-primary{border-top-color:#3c8dbc;background:0 0}.radio{display:inline}.radio>label{margin-right:30px}.validationError {color: #ff0c0c;}.button-submit{margin-top: 20px;margin-bottom: 20px}.ck.ck-content.ck-editor__editable {height: 150px;}span.ck-file-dialog-button {display: none;}.steps.clearfix.text-center{margin-top: 20px;padding-bottom: 20px;}.sss{padding: 8}.pwa-editor-bar-panel {display: none !important;}
  </style>
  @endsection
  @section('footerSection')

  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var title = $('#title').val();
                if (title == "") {
                    $("span#title_error").html('This field is required!');
                    $("input#title").focus();
                    return false;
                }

                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('AgentItineraryForm'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('agent_itinerary_update', $agentData->id) }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#gif').hide();
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
      $('#pdf_name').trigger('click');
    }    
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  @endsection